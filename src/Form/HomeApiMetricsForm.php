<?php

namespace Drupal\home_api_metrics\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the home api metrics entity edit forms.
 */
class HomeApiMetricsForm extends ContentEntityForm {

  /**
   * Drupal renderer service.
   *
   * @var Drupal\Core\Render\RendererInterface
   */
  public RendererInterface $renderer;

  /**
   * {@inheritDoc}
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info, TimeInterface $time, RendererInterface $renderer) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->renderer = $renderer;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('renderer'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'home_api_metrics.edit';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\home_api_mterics\HomeApiMetricsEntity $entity */
    $form = parent::buildForm($form, $form_state);

    $form['warning'] = [
      '#type' => 'markup',
      '#markup' => 'HOME API metrics should not be modified!',
      '#weight' => 1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => $this->renderer->render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New home api metrics %label has been created.', $message_arguments));
      $this->logger('home_api_metrics')->notice('Created new home api metrics %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The home api metrics %label has been updated.', $message_arguments));
      $this->logger('home_api_metrics')->notice('Updated new home api metrics %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.home_api_metrics.canonical', ['home_api_metrics' => $entity->id()]);
  }

}
