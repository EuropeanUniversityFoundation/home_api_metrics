<?php

namespace Drupal\home_api_metrics\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\DateTime\Time;
use Drupal\home_api_metrics\HomeApiMetricsService;

/**
 * Middleware for the HOME API.
 */
class HomeApiMetricsStatsController extends ControllerBase {

  /**
   * HOME API Metrics Service.
   *
   * @var \Drupal\home_api_metrics\HomeApiMetricsService
   */
  protected $homeApiMetricsService;

  /**
   * Time Service.
   *
   * @var \Drupal\Component\DateTime\Time
   */
  protected $time;

  /**
   * Constructor.
   */
  public function __construct(
    HomeApiMetricsService $home_api_metrics_service,
    Time $time
  ) {
    $this->homeApiMetricsService = $home_api_metrics_service;
    $this->time = $time;
  }

  /**
   * Gets services from the container for the Controller.
   */
  public static function create(
    ContainerInterface $container
  ) {
    return new static(
      $container->get('home_api_metrics.service'),
      $container->get('datetime.time')
    );
  }

  /**
   * Entry point of the open route controller.
   */
  public function handleModuleStats(Request $request): JsonResponse {
    $module_stats = $this->homeApiMetricsService->getModuleStats();

    return new JsonResponse($module_stats);
  }

  /**
   * Entry point of the details controller.
   */
  public function handleProviderStats(Request $request) {
    $provider_stats = $this->homeApiMetricsService->getProviderStats();

    return new JsonResponse($provider_stats);
  }

}
