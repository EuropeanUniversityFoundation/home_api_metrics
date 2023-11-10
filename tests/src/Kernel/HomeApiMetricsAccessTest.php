<?php

namespace Drupal\Tests\home_api_middleware\Kernel;

use Drupal\Tests\home_api_metrics\Kernel\HomeApiMetricsTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests endpoint access with different users.
 *
 * @group home_api_middleware
 */
class HomeApiMetricsAccessTest extends HomeApiMetricsTestBase {

  use UserCreationTrait;

  /**
   * Tests access as anonymous user.
   *
   * @return void
   *   Void.
   */
  public function testAnonymousAccessModuleOpen() {
    $this->accountSwitcher->switchTo($this->anonymousUser);

    $request = $this->createRequest('/api/accommodation/metrics/module/open', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 403);
  }

  /**
   * Tests with a user with correct permissions.
   *
   * @return void
   *   Void.
   */
  public function testAuthenticatedUserModuleOpen() {
    $this->accountSwitcher->switchTo($this->authenticatedUser);

    $request = $this->createRequest('/api/accommodation/metrics/module/open', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
  }

  /**
   * Tests access as anonymous user.
   *
   * @return void
   *   Void.
   */
  public function testAnonymousAccessProviderOpen() {
    $this->accountSwitcher->switchTo($this->anonymousUser);

    $request = $this->createRequest('/api/accommodation/metrics/provider/open/ProviderName', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 403);
  }

  /**
   * Tests with a user with correct permissions.
   *
   * @return void
   *   Void.
   */
  public function testAuthenticatedUserProviderOpen() {
    $this->accountSwitcher->switchTo($this->authenticatedUser);

    $request = $this->createRequest('/api/accommodation/metrics/provider/open/ProviderName', 'POST');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
  }

  /**
   * Tests access as anonymous user.
   *
   * @return void
   *   Void.
   */
  public function testAnonymousAccessModuleStats() {
    $this->accountSwitcher->switchTo($this->anonymousUser);

    $request = $this->createRequest('/api/accommodation/metrics/module/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 403);
  }

  /**
   * Tests with a user with insufficient permissions.
   *
   * @return void
   *   Void.
   */
  public function testAuthenticatedUserModuleStats() {
    $this->accountSwitcher->switchTo($this->authenticatedUser);

    $request = $this->createRequest('/api/accommodation/metrics/module/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 403);
  }

  /**
   * Tests with a user with correct permissions.
   *
   * @return void
   *   Void.
   */
  public function testAllEndpointsUserModuleStats() {
    $this->accountSwitcher->switchTo($this->allEndpointsUser);

    $request = $this->createRequest('/api/accommodation/metrics/module/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
  }

  /**
   * Tests access as anonymous user.
   *
   * @return void
   *   Void.
   */
  public function testAnonymousAccessProviderStats() {
    $this->accountSwitcher->switchTo($this->anonymousUser);

    $request = $this->createRequest('/api/accommodation/metrics/provider/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 403);
  }

  /**
   * Tests with a user with insufficient permissions.
   *
   * @return void
   *   Void.
   */
  public function testAuthenticatedUserProviderStats() {
    $this->accountSwitcher->switchTo($this->authenticatedUser);

    $request = $this->createRequest('/api/accommodation/metrics/provider/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 403);
  }

  /**
   * Tests with a user with correct permissions.
   *
   * @return void
   *   Void.
   */
  public function testAllEndpointsUserProviderStats() {
    $this->accountSwitcher->switchTo($this->allEndpointsUser);

    $request = $this->createRequest('/api/accommodation/metrics/provider/stats', 'GET');
    $response = $this->processRequest($request);

    $this->assertEquals($response->getStatusCode(), 200);
  }

}
