<?php

namespace Drupal\home_api_metrics\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the home api metrics entity edit forms.
 */
class HomeApiMetricsForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

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
