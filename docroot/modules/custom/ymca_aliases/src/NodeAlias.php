<?php
/**
 * @file
 * Contains node alias builder class.
 */

namespace Drupal\ymca_aliases;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Path\AliasManager;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Builds an alias for a node.
 *
 * @package Drupal\ymca_aliases.
 */
class NodeAlias {

  /**
   * News taxonomy term id.
   *
   * @var int
   */
  const NEWS_TERM_ID = 6;

  /**
   * Url cleaner.
   *
   * @var UrlCleaner
   */
  protected $urlCleaner;

  /**
   * Alias manager.
   *
   * @var AliasManager
   */
  protected $aliasManager;

  /**
   * NodeAlias constructor.
   */
  public function __construct(UrlCleaner $url_cleaner, AliasManager $alias_manager) {
    $this->urlCleaner = $url_cleaner;
    $this->aliasManager = $alias_manager;
  }

  /**
   * Get alias.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node object.
   *
   * @return null|string
   *   Generated alias.
   */
  public function getAlias(NodeInterface $node) {
    $alias = NULL;

    switch ($node->bundle()) {
      case 'blog':
        $tz = new DrupalDateTime(\Drupal::config('system.date')->get('timezone')['default']);
        $created = $node->get('created')->getValue()[0]['value'];
        $date = DrupalDateTime::createFromTimestamp($created, $tz);

        $url_parts = [
          'year' => $date->format('Y'),
          'month' => $date->format('m'),
          'day' => $date->format('d'),
          'id' => $node->id(),
          'suffix' => $this->urlCleaner->clean($node->getTitle()),
        ];

        // The post belongs to a camp or a location.
        $section = $node->get('field_site_section');
        if (!$section->isEmpty()) {
          $id = $section->getValue()[0]['target_id'];
          $target_node = Node::load($id);
          if ($target_node->bundle() == 'camp') {
            $target_alias = $this->aliasManager->getAliasByPath('/node/' . $id);
            preg_match('/\/camps\/(\w+)/', $target_alias, $test);
            if (!empty($test[1])) {
              $url_parts = array_merge(['prefix' => $test[1] . '_news__events'], $url_parts);
              $alias = '/' . implode('/', $url_parts);
              break;
            }
          }
        }

        // Check whether the post has 'news' tag.
        $tags = $node->get('field_tags');
        if (!$tags->isEmpty()) {
          foreach ($tags->getValue() as $item) {
            if ($item['target_id'] == self::NEWS_TERM_ID) {
              $url_parts = array_merge(['prefix' => 'news'], $url_parts);
              $alias = '/' . implode('/', $url_parts);
              break;
            }
          }
        }

        // Finally set 'blog' prefix.
        $url_parts = array_merge(['prefix' => 'blog'], $url_parts);
        $alias = '/' . implode('/', $url_parts);

        break;
    }

    return $alias;
  }

}
