<?php

namespace Drupal\ffw_custom\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Office information entities.
 *
 * @ingroup ffw_custom
 */
interface OfficeEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Office information name.
   *
   * @return string
   *   Name of the Office information.
   */
  public function getName();

  /**
   * Sets the Office information name.
   *
   * @param string $name
   *   The Office information name.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeEntityInterface
   *   The called Office information entity.
   */
  public function setName($name);

  /**
   * Gets the Office information creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Office information.
   */
  public function getCreatedTime();

  /**
   * Sets the Office information creation timestamp.
   *
   * @param int $timestamp
   *   The Office information creation timestamp.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeEntityInterface
   *   The called Office information entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Office information revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Office information revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeEntityInterface
   *   The called Office information entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Office information revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Office information revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeEntityInterface
   *   The called Office information entity.
   */
  public function setRevisionUserId($uid);

}
