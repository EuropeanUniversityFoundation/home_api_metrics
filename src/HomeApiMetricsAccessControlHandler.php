<?php

namespace Drupal\home_api_metrics;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the as entity type.
 */
class HomeApiMetricsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission(
          $account, 'administer home api metrics'
        );

      case 'update':
        return AccessResult::allowedIfHasPermissions(
          $account, ['administer home api metrics']
        );

      case 'delete':
        return AccessResult::allowedIfHasPermissions(
          $account, ['administer home api metrics']
        );

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions(
      $account, ['create home api metrics', 'administer home api metrics'], 'OR'
    );
  }

}
