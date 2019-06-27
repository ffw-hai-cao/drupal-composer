<?php

/**
 * @file
 * Contains office_entity.page.inc.
 *
 * Page callback for Office information entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Office information templates.
 *
 * Default template: office_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_office_entity(array &$variables) {
  // Fetch OfficeEntity Entity Object.
  $office_entity = $variables['elements']['#office_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
