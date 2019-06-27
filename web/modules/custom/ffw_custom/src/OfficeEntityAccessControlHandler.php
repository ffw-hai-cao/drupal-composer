<?php

namespace Drupal\ffw_custom;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Office information entity.
 *
 * @see \Drupal\ffw_custom\Entity\OfficeEntity.
 */
class OfficeEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ffw_custom\Entity\OfficeEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished office information entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published office information entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit office information entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete office information entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add office information entities');
  }

}
