<?php

namespace Drupal\entity_clone\EntityClone\Config;

use Drupal\Component\Utility\Random;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\entity_clone\EntityClone\EntityCloneInterface;
use Drupal\page_manager\Entity\PageVariant;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PageConfigEntityCloneBase.
 */
class PageConfigEntityCloneBase extends ConfigEntityCloneBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The entity type ID.
   *
   * @var string
   */
  protected $entityTypeId;

  /**
   * Constructs a new PageConfigEntityCloneBase.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The entity type manager.
   * @param string $entity_type_id
   *   The entity type ID.
   */
  public function __construct(EntityTypeManager $entity_type_manager, $entity_type_id) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityTypeId = $entity_type_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $container->get('entity_type.manager'),
      $entity_type->id()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function cloneEntity(EntityInterface $entity, EntityInterface $cloned_entity, $properties = []) {
    /** @var \Drupal\core\Config\Entity\ConfigEntityInterface $cloned_entity */
    $id_key = $this->entityTypeManager->getDefinition($this->entityTypeId)->getKey('id');
    $label_key = $this->entityTypeManager->getDefinition($this->entityTypeId)->getKey('label');
    // @todo make this dynamic if needed.
    $path_key = 'path';


    // Set new entity properties.
    if (isset($properties['id'])) {
      if ($id_key) {
        $cloned_entity->set($id_key, $properties['id']);
      }
      unset($properties['id']);
    }

    if (isset($properties['label'])) {
      if ($label_key) {
        $cloned_entity->set($label_key, $properties['label']);
      }
      unset($properties['label']);
    }

    if (isset($properties['path'])) {
      if ($path_key) {
        $cloned_entity->set($path_key, $properties['path']);
      }
      unset($properties['path']);
    }

    foreach ($properties as $key => $property) {
      $cloned_entity->set($key, $property);
    }

    $cloned_entity->save();

    $variants = $entity->getVariants();
    $rand = new Random();
    /** @var PageVariant $variant */
    foreach ($variants as $variant) {
      $hash = strtolower($rand->name(8, TRUE));
      $var = $variant->createDuplicate();
      $var->set('page', $cloned_entity->id());
      $new_variants[$variant->id() . $hash] = $var->set('id', $variant->id() . $hash);
      $new_variants[$variant->id() . $hash]->save();
    }
    
    $cloned_entity->set('variants', $new_variants);
    $cloned_entity->save();
    return $cloned_entity;

  }

}
