<?php

namespace Drupal\home_api_metrics\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\home_api_metrics\HomeApiMetricsInterface;

/**
 * Defines the home api metrics entity class.
 *
 * @ContentEntityType(
 *   id = "home_api_metrics",
 *   label = @Translation("HOME API metrics"),
 *   label_collection = @Translation("HOME API metrics"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\home_api_metrics\HomeApiMetricsListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\home_api_metrics\HomeApiMetricsAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\home_api_metrics\Form\HomeApiMetricsForm",
 *       "edit" = "Drupal\home_api_metrics\Form\HomeApiMetricsForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "home_api_metrics",
 *   admin_permission = "administer home api metrics",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/home-api-metrics/add",
 *     "canonical" = "/home_api_metrics/{home_api_metrics}",
 *     "edit-form" = "/admin/content/home-api-metrics/{home_api_metrics}/edit",
 *     "delete-form" = "/admin/content/home-api-metrics/{home_api_metrics}/delete",
 *     "collection" = "/admin/content/home-api-metrics"
 *   },
 * )
 */
class HomeApiMetrics extends ContentEntityBase implements HomeApiMetricsInterface {

  /**
   * {@inheritdoc}
   */
  public function getTitle(): string {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title): HomeApiMetricsInterface {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getClickCount(): int {
    return $this->get('click_count')->value ? $this->get('click_count')->value : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setClickCount($count): HomeApiMetrics {
    $this->set('click_count', $count);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getYear(): int {
    return $this->get('year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setYear($year): HomeApiMetrics {
    $this->set('year', $year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMonth(): int {
    return $this->get('month')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMonth($month): HomeApiMetrics {
    $this->set('month', $month);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup(): ?string {
    return $this->get('group')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setGroup($group = NULL): HomeApiMetrics {
    $this->set('group', $group);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the home api metrics entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'type' => 'string_textfield',
        'weight' => -20,
      ]);

    $fields['year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setDescription(t('Year clicks were completed'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'type' => 'integer',
        'weight' => -40,
      ]);

    $fields['month'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Month'))
      ->setDescription(t('Month clicks were completed'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'type' => 'integer',
        'weight' => -30,
      ]);

    $fields['group'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Provider name'))
      ->setDescription('Housing provider name')
      ->setRequired(FALSE)
      ->setSettings(
        [
          'max_length' => 255,
          'default_value' => NULL,
        ])
      ->setDisplayOptions('view', [
        'type' => 'text',
        'weight' => -30,
      ]);

    $fields['click_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Click count'))
      ->setDescription('Number of clicks aggregated per months and per provider')
      ->setSettings(
        [
          'default_value' => 0,
        ]
      )
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'type' => 'integer',
        'weight' => -20,
      ]);

    return $fields;
  }

}
