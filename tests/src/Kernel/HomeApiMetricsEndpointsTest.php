<?php

namespace Drupal\Tests\home_api_metrics\Kernel;

use Drupal\Tests\home_api_metrics\Kernel\HomeApiMetricsTestBase;

/**
 * Test description.
 *
 * @group home_api_metrics
 */
class HomeApiMetricsEndpointsTest extends HomeApiMetricsTestBase {

  /**
   * Tests if status code is 200 and response is JSON.
   *
   * @return void
   *   Void.
   */
  public function testMetricsOpen() {
    $request = $this->createRequest('/api/accommodation/metrics/module/open', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
    $this->assertJson($response->getContent());
  }

  /**
   * Tests if home_metrics_entity is added.
   *
   * @return void
   *   Void.
   */
  public function testMetricsOpenAdded() {
    $request = $this->createRequest('/api/accommodation/metrics/module/open', 'POST');
    $response = $this->processRequest($request);

    $entityManager = \Drupal::service('entity_type.manager');
    $entityStorage = $entityManager->getStorage('home_api_metrics');

    $ids = $entityStorage
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition('group', NULL, 'IS NULL')
      ->sort('year')
      ->sort('month')
      ->execute();

    $entity = ($entityStorage->load($ids[array_key_first($ids)]));
    $date = new \DateTime();
    $year = intval($date->format('Y'));
    $month = intval($date->format('n'));

    // If it's the turn of the month between the query and $date declaration, the two values could differ.
    $this->assertContains($year, [$entity->getYear(), $entity->getYear() + 1]);
    // If it's the turn of the year between the query and the $date declaration, the two values could differ.
    $this->assertContains($month, [$entity->getMonth(), $entity->getMonth() + 1]);
  }

  /**
   * Tests if status code is 200 and response is JSON.
   *
   * @return void
   *   Void.
   */
  public function testMetricsProviderOpen() {
    $request = $this->createRequest('/api/accommodation/metrics/provider/open/HousingCompany', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
    $this->assertJson($response->getContent());
  }

  /**
   * Tests if home_metrics_entity is added.
   *
   * @return void
   *   Void.
   */
  public function testMetricsProviderAdded() {
    $request = $this->createRequest('/api/accommodation/metrics/provider/open/HousingCompany', 'POST');
    $response = $this->processRequest($request);

    $entityManager = \Drupal::service('entity_type.manager');
    $entityStorage = $entityManager->getStorage('home_api_metrics');

    $ids = $entityStorage
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition('group', NULL, 'IS NOT NULL')
      ->sort('year')
      ->sort('month')
      ->sort('group')
      ->execute();

    $entity = ($entityStorage->load($ids[array_key_first($ids)]));
    $date = new \DateTime();
    $year = intval($date->format('Y'));
    $month = intval($date->format('n'));
    $group = 'HousingCompany';

    // If it's the turn of the month in the split second between the query and $date declaration, the two values could differ.
    $this->assertContains($year, [$entity->getYear(), $entity->getYear() + 1]);
    // If it's the turn of the year in the split second between the query and the $date declaration, the two values could differ.
    $this->assertContains($month, [$entity->getMonth(), $entity->getMonth() + 1]);
    $this->assertEquals($group, $entity->getGroup());
  }

  /**
   * Tests if status code is 200 and response is JSON.
   *
   * @return void
   *   Void.
   */
  public function testMetricsGeneralStats() {
    $request = $this->createRequest('/api/accommodation/metrics/module/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
    $this->assertJson($response->getContent());
  }

  /**
   * Tests if status code is 200 and response is JSON.
   *
   * @return void
   *   Void.
   */
  public function testMetricsProviderStats() {
    $request = $this->createRequest('/api/accommodation/metrics/provider/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
    $this->assertJson($response->getContent());
  }

}
