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
class HomeApiMetricsController extends ControllerBase {

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
  public function handleOpen(Request $request): JsonResponse {
    $time = $this->time->getRequestTime();
    // $time = \strtotime('2022-05-28');
    $entity = $this->homeApiMetricsService->addOpen($time);

    return new JsonResponse(
      [
        'year' => $entity->getYear(),
        'month' => $entity->getMonth(),
        'click_count' => $entity->getClickCount(),
      ]
    );
  }

  /**
   * Entry point of the details controller.
   */
  public function handleDetails(Request $request) {
    $time = $this->time->getRequestTime();
    $group = $request->query->get('group');

    $entity = $this->homeApiMetricsService->addDetails($time, $group);

    return new JsonResponse(
      [
        'year' => $entity->getYear(),
        'month' => $entity->getMonth(),
        'group' => $entity->getGroup(),
        'click_count' => $entity->getClickCount(),
      ]
    );
  }

}
