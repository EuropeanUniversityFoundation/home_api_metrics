<?php

namespace Drupal\home_api_metrics;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Handles HomeApiMetrics Entities.
 */
class HomeApiMetricsService {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Entity storage from EntityTypeManager.
   *
   * @var Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Constructs a HomeApiMetricsService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TimeInterface $time) {
    $this->entityTypeManager = $entity_type_manager;
    $this->time = $time;
    $this->entityStorage = $this->entityTypeManager->getStorage('home_api_metrics');
  }

  /**
   * Increments the Metrics open count and saves entity.
   *
   * @param int $time
   *   Unix timestamp to derive year and month from.
   *
   * @return \Drupal\home_api_metrics\HomeApiMetrics
   *   The modified HomeApiMetrics entity.
   */
  public function addModuleOpen(int $time) {
    $entity = $this->getModuleOpenByTimestamp($time);

    $click_count = $entity->getClickCount();
    $entity = $entity->setClickCount($click_count + 1);
    $entity->save();

    return $entity;
  }

  /**
   * Increments the Metrics details count and saves entity.
   *
   * @param int $time
   *   Unix timestamp to derive year and month from.
   * @param string $group
   *   Group to increment the clicks of.
   *
   * @return \Drupal\home_api_metrics\HomeApiMetrics
   *   The modified HomeApiMetrics entity.
   */
  public function addProviderOpen(int $time, string $group) {
    $entity = $this->getProviderOpenByTimestamp($time, $group);

    $click_count = $entity->getClickCount();
    $entity = $entity->setClickCount($click_count + 1);
    $entity->save();

    return $entity;
  }

  /**
   * Returns home_api_metrics_entity for a month in a year.
   */
  public function getModuleOpenByTimestamp(int $time) {
    $year = $this->getYearFromTimestamp($time);
    $month = $this->getMonthFromTimestamp($time);

    // Query the metrics entity for a specific year and month.
    $id = $this->entityStorage
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('year', $year)
      ->condition('month', $month)
      ->condition('group', NULL, 'IS NULL')
      ->execute();

    $key = \array_key_first($id);

    // If no metrics entity found for specific year and month create one.
    if (empty($id)) {
      $entity = $this->entityStorage->create([
        'title' => $year . ' - ' . $month,
        'year' => $year,
        'month' => $month,
        'group' => NULL,
      ]);

      $entity->save();
    }
    else {
      $entity = $this->entityStorage->load($id[$key]);
    }

    return $entity;
  }

  /**
   * Returns home_api_metrics_entity for a month and a provider in a year.
   */
  public function getProviderOpenByTimestamp(int $time, string $group) {
    $year = $this->getYearFromTimestamp($time);
    $month = $this->getMonthFromTimestamp($time);

    // Query the metrics entity for a specific year, month and group.
    $id = $this->entityStorage
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('year', $year)
      ->condition('month', $month)
      ->condition('group', $group)
      ->execute();

    $key = \array_key_first($id);

    // If no metrics entity found for specific year, month and group create one.
    if (empty($id)) {
      $entity = $this->entityStorage->create([
        'title' => $year . ' - ' . $month . ': ' . $group,
        'year' => $year,
        'month' => $month,
        'group' => $group,
      ]);

      $entity->save();
    }
    else {
      $entity = $this->entityStorage->load($id[$key]);
    }

    return $entity;
  }

  /**
   * Gets year from timestamp.
   *
   * @param int $time
   *   Timestamp.
   *
   * @return int
   *   Current year as int.
   */
  protected function getYearFromTimestamp(int $time) {
    return date('Y', $time);
  }

  /**
   * Gets month from timestamp.
   *
   * @param int $time
   *   Timestamp.
   *
   * @return int
   *   Current month as int.
   */
  protected function getMonthFromTimestamp(int $time) {
    return date('m', $time);
  }

  /**
   * Gets statistics for module open.
   *
   * @return array
   *   Returns stats in format:
   *   [
   *     [year] =>
   *       [
   *         'month' => int
   *         'click_count' => int
   *       ]
   *   ]
   */
  public function getModuleStats() {
    $stats = [];
    // Query the metrics entity.
    $ids = $this->entityStorage->getQuery()
      ->condition('group', NULL, 'IS NULL')
      ->sort('year')
      ->sort('month')
      ->execute();

    if (!empty($ids)) {
      $entities = $this->entityStorage->loadMultiple($ids);
    }
    else {
      return [];
    }

    foreach ($entities as $entity) {
      $year = $entity->getYear();
      $month = $entity->getMonth();
      $click_count = $entity->getClickCount();

      if (!isset($stats[$year])) {
        $stats[$year] = [];
      }
      $stats[$year][] = [
        'month' => $month,
        'click_count' => $click_count,
      ];
    }

    return $stats;
  }

  /**
   * Gets statistics for provider opens.
   *
   * @return array
   *   Returns stats in format:
   *   [
   *     [year] =>
   *       [
   *         'provider_id' => string
   *         'month' => int
   *         'click_count' => int
   *       ]
   *   ]
   */
  public function getProviderStats() {
    $stats = [];
    // Query the metrics entity.
    $ids = $this->entityStorage->getQuery()
      ->condition('group', NULL, 'IS NOT NULL')
      ->sort('year')
      ->sort('month')
      ->sort('group')
      ->execute();

    if (!empty($ids)) {
      $entities = $this->entityStorage->loadMultiple($ids);
    }
    else {
      return [];
    }

    foreach ($entities as $entity) {
      $year = $entity->getYear();
      $month = $entity->getMonth();
      $group = $entity->getGroup();
      $click_count = $entity->getClickCount();

      if (!isset($stats[$year])) {
        $stats[$year] = [];
      }
      $stats[$year][] = [
        'provider_id' => $group,
        'month' => $month,
        'click_count' => $click_count,
      ];
    }

    return $stats;
  }

}
