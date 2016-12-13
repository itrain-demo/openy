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
   * Test getSchoolsByLocation().
   */
  public function testGetSchoolsByLocation() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $storage->getSchoolsByLocation(106);
  }

  /**
   * Test getChildCareProgramsByLocation().
   */
  public function testGetChildCareProgramsByLocation() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $storage->getChildCareProgramsByLocation(106);
  }

  /**
   * Test getSchoolsByChildCareProgramId().
   */
  public function testGetSchoolsByChildCareProgramId() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $storage->getSchoolsByChildCareProgramId(8595);
  }

  /**
   * Test getLocationsByChildCareProgramId().
   */
  public function testGetLocationsByChildCareProgramId() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $locations = $storage->getLocationsByChildCareProgramId(9532);
    $this->assertArrayHasKey(123, $locations);
  }

  /**
   * Test getSessionsByProgramAndLocation().
   */
  public function testGetSessionsByProgramAndLocation() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $storage->getSessionsByProgramAndLocation(8415, 106);
  }

  /**
   * Test getProgramsByLocation().
   */
  public function testGetProgramsByLocation() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $storage->getProgramsByLocation(106);
  }

  /**
   * Test getRegistrationLinkByProgram().
   */
  public function testGetRegistrationLink() {
    $storage = \Drupal::service('ygh_programs_search.data_storage');
    $storage->getRegistrationLink(16, 34);
  }

}