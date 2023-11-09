<?php

namespace Drupal\Tests\home_api_metrics\Kernel;

use Drupal\Tests\home_api_metrics\Kernel\HomeApiMetricsTestBase;

/**
 * Test description.
 *
 * @group home_api_middleware
 */
class HomeApiMetricsEndpointsTest extends HomeApiMetricsTestBase {

  /**
   * Tests if status code is 200 and response is JSON.
   *
   * @return void
   *   Void.
   */
  public function testMetricsOpened() {
    $request = $this->createRequest('/api/accommodation/metrics/module/open', 'POST');
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
  public function testMetricsProviderOpened() {
    $request = $this->createRequest('/api/accommodation/metrics/provider/open/HousingCompany', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
    $this->assertJson($response->getContent());
  }

}
