<?php

namespace Drupal\home_api_metrics;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a home api metrics entity type.
 */
interface HomeApiMetricsInterface extends ContentEntityInterface {

  /**
   * Gets the home api metrics title.
   *
   * @return string
   *   Title of the home api metrics.
   */
  public function getTitle(): string;

  /**
   * Sets the home api metrics title.
   *
   * @param string $title
   *   The home api metrics title.
   *
   * @return \Drupal\home_api_metrics\HomeApiMetricsInterface
   *   The called home api metrics entity.
   */
  public function setTitle(string $title): HomeApiMetricsInterface;

  /**
   * Gets the year.
   *
   * @return int
   *   Returns year value.
   */
  public function getYear(): int;

  /**
   * Sets year.
   *
   * @param int $year
   *   Year of metrics data.
   *
   * @return HomeApiMetricsInterface
   *   The modified entity.
   */
  public function setYear(int $year): HomeApiMetricsInterface;

  /**
   * Gets the month.
   *
   * @return int
   *   Returns month number.
   */
  public function getMonth(): int;

  /**
   * Sets month.
   *
   * @param int $month
   *   Month of metrics data.
   *
   * @return HomeApiMetricsInterface
   *   The modified entity.
   */
  public function setMonth(int $month): HomeApiMetricsInterface;

  /**
   * Gets the number of clicks in the year + month pair.
   *
   * @return int
   *   Returns number of clicks value.
   */
  public function getClickCount(): int;

  /**
   * Sets click count for year and month.
   *
   * @param int $clickCount
   *   Click count for year and month.
   *
   * @return HomeApiMetricsInterface
   *   The modified entity.
   */
  public function setClickCount(int $clickCount): HomeApiMetricsInterface;

  /**
   * Gets the group.
   *
   * Returns the provider name for provider specific metrics.
   * Returns empty if metrics is general (counts overall clicks on module)
   *
   * @return string
   *   String if provider metrics, empty if general metrics
   */
  public function getGroup(): ?string;

  /**
   * Sets group value.
   *
   * Should be empty or value of the provider id.
   *
   * @return HomeApiMetricsInterface
   *   The modified entity.
   */
  public function setGroup(string $group = NULL): HomeApiMetricsInterface;

}
