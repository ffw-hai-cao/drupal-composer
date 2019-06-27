<?php

namespace Drupal\ffw_custom\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\ffw_custom\Entity\OfficeEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OfficeEntityController.
 *
 *  Returns responses for Office information routes.
 */
class OfficeEntityController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new OfficeEntityController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Office information revision.
   *
   * @param int $office_entity_revision
   *   The Office information revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($office_entity_revision) {
    $office_entity = $this->entityTypeManager()->getStorage('office_entity')
      ->loadRevision($office_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('office_entity');

    return $view_builder->view($office_entity);
  }

  /**
   * Page title callback for a Office information revision.
   *
   * @param int $office_entity_revision
   *   The Office information revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($office_entity_revision) {
    $office_entity = $this->entityTypeManager()->getStorage('office_entity')
      ->loadRevision($office_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $office_entity->label(),
      '%date' => $this->dateFormatter->format($office_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Office information.
   *
   * @param \Drupal\ffw_custom\Entity\OfficeEntityInterface $office_entity
   *   A Office information object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OfficeEntityInterface $office_entity) {
    $account = $this->currentUser();
    $office_entity_storage = $this->entityTypeManager()->getStorage('office_entity');

    $langcode = $office_entity->language()->getId();
    $langname = $office_entity->language()->getName();
    $languages = $office_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $office_entity->label()]) : $this->t('Revisions for %title', ['%title' => $office_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all office information revisions") || $account->hasPermission('administer office information entities')));
    $delete_permission = (($account->hasPermission("delete all office information revisions") || $account->hasPermission('administer office information entities')));

    $rows = [];

    $vids = $office_entity_storage->revisionIds($office_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\ffw_custom\OfficeEntityInterface $revision */
      $revision = $office_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $office_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.office_entity.revision', [
            'office_entity' => $office_entity->id(),
            'office_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $office_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.office_entity.translation_revert', [
                'office_entity' => $office_entity->id(),
                'office_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.office_entity.revision_revert', [
                'office_entity' => $office_entity->id(),
                'office_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.office_entity.revision_delete', [
                'office_entity' => $office_entity->id(),
                'office_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['office_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
