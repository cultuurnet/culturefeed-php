<?php

use PHPUnit\Framework\TestCase;

class CultureFeed_Uitpas_Counter_EmployeeTest extends TestCase {

  /**
   * @var CultureFeed_Uitpas_Counter_Employee
   */
  protected $employee;

  /**
   * @var array
   */
  protected $permissions;

  /**
   * @var array
   */
  protected $groups;

  public function setUp() {
    $cardSystem1 = new \CultureFeed_Uitpas_Counter_EmployeeCardSystem();
    $cardSystem1->permissions = array(
      'permission1',
      'permission2',
    );
    $cardSystem1->groups = array(
      'group1',
      'group2',
    );

    $cardSystem2 = new \CultureFeed_Uitpas_Counter_EmployeeCardSystem();
    $cardSystem2->groups = array(
      'group2',
      'group3',
    );

    $cardSystem3 = new \CultureFeed_Uitpas_Counter_EmployeeCardSystem();
    $cardSystem3->permissions = array(
      'permission2',
      'permission3',
    );

    $this->employee = new \CultureFeed_Uitpas_Counter_Employee();
    $this->employee->id = 'id';
    $this->employee->consumerKey = 'consumerKey';
    $this->employee->cardSystems = array(
      $cardSystem1,
      $cardSystem2,
      $cardSystem3,
    );

    $this->permissions = array(
      'permission1',
      'permission2',
      'permission3',
    );

    $this->groups = array(
      'group1',
      'group2',
      'group3',
    );
  }

  public function testJsonEncoding() {
    $json = json_encode($this->employee);
    $decoded = json_decode($json);

    $this->assertEquals($this->permissions, $decoded->permissions);
    $this->assertEquals($this->groups, $decoded->groups);
  }

  public function testGetPermissionsFromCardSystems() {
    $this->assertEquals($this->permissions, $this->employee->getPermissionsFromCardSystems());
    $emptyEmployee = new CultureFeed_Uitpas_Counter_Employee();
    $this->assertEquals(array(), $emptyEmployee->getPermissionsFromCardSystems());
  }

  public function testGetGroupsFromCardSystems() {
    $this->assertEquals($this->groups, $this->employee->getGroupsFromCardSystems());
    $emptyEmployee = new CultureFeed_Uitpas_Counter_Employee();
    $this->assertEquals(array(), $emptyEmployee->getGroupsFromCardSystems());
  }

}
