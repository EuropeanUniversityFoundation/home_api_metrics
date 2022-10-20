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
  public function addOpen(int $time) {
    $entity = $this->getOpenByTimestamp($time);

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
  public function addDetails(int $time, string $group) {
    $entity = $this->getDetailsByTimestampAndGroup($time, $group);

    $click_count = $entity->getClickCount();
    $entity = $entity->setClickCount($click_count + 1);
    $entity->save();

    return $entity;
  }

  /**
   * Returns home_api_metrics_entity for a month in a year.
   */
  public function getOpenByTimestamp(int $time) {
    $year = $this->getYearFromTimestamp($time);
    $month = $this->getMonthFromTimestamp($time);

    // Query the metrics entity for a specific year and month.
    $id = $this->entityStorage->getQuery()
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
  public function getDetailsByTimestampAndGroup(int $time, string $group) {
    $year = $this->getYearFromTimestamp($time);
    $month = $this->getMonthFromTimestamp($time);

    // Query the metrics entity for a specific year, month and group.
    $id = $this->entityStorage->getQuery()
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

}
