<?php

namespace Drupal\Tests\home_api_metrics\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Session\AccountSwitcherInterface;
use Drupal\home_api_metrics\HomeApiMetricsService;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test base for HOME API metrics tests.
 */
class HomeApiMetricsTestBase extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'home_api_metrics',
    'system',
    'user',
  ];

  /**
   * Admin.
   *
   * @var \Drupal\user\Entity\User
   */
  public User $adminUser;

  /**
   * User with access to all endpoints.
   *
   * @var \Drupal\user\Entity\User
   */
  public User $allEndpointsUser;

  /**
   * Authenticated user.
   *
   * @var \Drupal\user\Entity\User
   */
  public User $authenticatedUser;

  /**
   * Anonymous user.
   *
   * @var \Drupal\user\Entity\User
   */
  public User $anonymousUser;

  /**
   * Account switcher service.
   *
   * @var \Drupal\Core\Session\AccountSwitcherInterface
   */
  public AccountSwitcherInterface $accountSwitcher;

  /**
   * HomeApiMetricsService.
   *
   * @var \Drupal\home_api_metrics\HomeApiMetricsService
   */
  public HomeApiMetricsService $homeApiMetricsService;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('home_api_metrics');

    $this->homeApiMetricsService = \Drupal::service('home_api_metrics.service');

    $this->adminUser = $this->createUser(values: ['uid' => 1]);
    $this->allEndpointsUser = $this->createUser([
      'use home api metrics endpoints',
      'access home api metrics statistics endpoint',
    ]);
    $this->authenticatedUser = $this->createUser(['use home api metrics endpoints']);
    $this->anonymousUser = $this->createUser(values: ['uid' => 0]);

    $this->accountSwitcher = \Drupal::service('account_switcher');
    $this->accountSwitcher->switchTo($this->allEndpointsUser);
  }

  /**
   * Creates request.
   */
  public function createRequest(string $uri, string $method, array $document = []): Request {
    $request = Request::create($uri, $method, [], [], [], [], $document ? Json::encode($document) : NULL);
    $request->headers->set('Accept', 'application/vnd.api+json');

    return $request;
  }

  /**
   * Handles a request with the http_kernel service.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   A preconstructed request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The returned response.
   */
  public function processRequest(Request $request)/*: JsonResponse*/ {
    return $this->container->get('http_kernel')->handle($request);
  }

}
