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
  public function getTitle();

  /**
   * Sets the home api metrics title.
   *
   * @param string $title
   *   The home api metrics title.
   *
   * @return \Drupal\home_api_metrics\HomeApiMetricsInterface
   *   The called home api metrics entity.
   */
  public function setTitle($title);

}
