<?php

namespace Drupal\ffw_custom;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\ffw_custom\Entity\OfficeEntityInterface;

/**
 * Defines the storage handler class for Office information entities.
 *
 * This extends the base storage class, adding required special handling for
 * Office information entities.
 *
 * @ingroup ffw_custom
 */
interface OfficeEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Office information revision IDs for a specific Office information.
   *
   * @param \Drupal\ffw_custom\Entity\OfficeEntityInterface $entity
   *   The Office information entity.
   *
   * @return int[]
   *   Office information revision IDs (in ascending order).
   */
  public function revisionIds(OfficeEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Office information author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Office information revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\ffw_custom\Entity\OfficeEntityInterface $entity
   *   The Office information entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OfficeEntityInterface $entity);

  /**
   * Unsets the language for all Office information with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
