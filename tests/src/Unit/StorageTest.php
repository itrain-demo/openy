<?php

namespace Drupal\Tests\ygh_programs_search\Unit;

/**
 * Class StorageTest.
 */
class StorageTest extends \PHPUnit_Framework_TestCase {

  /**
   * Backup globals.
   *
   * @var bool
   *
   * @see https://github.com/sebastianbergmann/phpunit/issues/451
   * @see https://github.com/silverstripe/silverstripe-behat-extension/commit/7ef575c961ef8a42646b9a30d5a37ad125290dce
   */
  protected $backupGlobals = FALSE;

  /**
   * Test getProgramsByLocation().
   */
  public function testGetProgramsByLocation() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $programs = $storage->getProgramsByLocation(91);
  }

}