<?php

/**
 * @file
 * Definition of Drupal\domain\Tests\DomainCreate
 */

namespace Drupal\domain\Tests;
use Drupal\domain\Plugin\Core\Entity\Domain;

/**
 * Tests the domain record creation API.
 */
class DomainCreate extends DomainTestBase {

  public static function getInfo() {
    return array(
      'name' => 'Domain record creation',
      'description' => 'Tests domain record CRUD API.',
      'group' => 'Domain',
    );
  }

  /**
   * Test initial domain creation.
   */
  function testDomainCreate() {
    // No domains should exist.
    $this->domainTableIsEmpty();

    // Create a new domain programmatically.
    $domain = domain_create();
    foreach (array('domain_id', 'hostname', 'name', 'machine_name') as $key) {
      $this->assertTrue(is_null($domain->{$key}), 'New $domain->' . $key . ' property is set to NULL.');
    }
    foreach (array('scheme', 'status', 'weight' , 'is_default') as $key) {
      $this->assertTrue(isset($domain->{$key}), 'New $domain->' . $key . ' property is set to default value: ' . $domain->{$key});
    }
    // Now add the additional fields and save.
    $domain->hostname = $_SERVER['HTTP_HOST'];
    $domain->machine_name = domain_machine_name($domain->hostname);
    $domain->name = 'Default';
    $domain->save();

    // Did it save correctly?
    $default_id = domain_default_id();
    $this->assertTrue(!empty($default_id), t('Default domain has been set.'));

    // Does it load correctly?
    $new_domain = domain_load($default_id);
    $this->assertTrue($new_domain->machine_name == $domain->machine_name, 'Domain loaded properly.');

    // Delete the domain.
    $domain->delete();

    // No domains should exist.
    $this->domainTableIsEmpty();

    // Try the create function with server inheritance.
    $domain = domain_create(TRUE);
    foreach (array('domain_id') as $key) {
      $this->assertTrue(is_null($domain->{$key}), 'New $domain->' . $key . ' property is set to NULL.');
    }
    foreach (array('hostname', 'name', 'machine_name', 'scheme', 'status', 'weight' , 'is_default') as $key) {
      $this->assertTrue(isset($domain->{$key}), 'New $domain->' . $key . ' property is set to a default value: ' . $domain->{$key});
    }
  }

}

  /**
   * Validate a domain hostname.

  function testDomainValidate() {

  }
   */