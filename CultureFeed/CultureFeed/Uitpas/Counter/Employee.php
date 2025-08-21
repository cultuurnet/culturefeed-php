<?php

class CultureFeed_Uitpas_Counter_Employee extends CultureFeed_Uitpas_ValueObject implements JsonSerializable {

  /**
   * The id of the counter
   *
   * @var string
   */
  public $id;

  /**
   * The consumer key of the counter
   *
   * @var string
   */
  public $consumerKey;

  /**
   * The name of the counter
   *
   * @var string
   */
  public $name;

  /**
   * The role of the member in the counter
   *
   * @var bool
   */
  public $role;

  /**
   * @var string
   */
  public $actorId;

  /**
   * @var array<CultureFeed_Uitpas_Counter_EmployeeCardSystem>
   */
  public array $cardSystems = [];


  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $counter = new CultureFeed_Uitpas_Counter_Employee();
    $counter->id = $object->xpath_str('id');
    $counter->consumerKey = $object->xpath_str('consumerKey');
    $counter->name = $object->xpath_str('name');
    $counter->role = $object->xpath_str('role');
    $counter->actorId = $object->xpath_str('actorId');

    foreach ($object->xpath('cardSystems/cardSystem') as $card_system) {
      $cardSystem = CultureFeed_Uitpas_Counter_EmployeeCardSystem::createFromXml($card_system);
      $counter->cardSystems[$cardSystem->id] = $cardSystem;
    }

    return $counter;
  }

  /**
   * @param integer $id
   *
   * @return boolean
   */
  public function inCardSystem($id) {
    return array_key_exists($id, $this->cardSystems);
  }

  /**
   * @return array
   */
  public function getPermissionsFromCardSystems() {
    return $this->getPropertyFromCardsystems('permissions');
  }

  /**
   * @return array
   */
  public function getGroupsFromCardSystems() {
    return $this->getPropertyFromCardsystems('groups');
  }

  /**
   * @param string $type
   * @return array
   */
  private function getPropertyFromCardsystems($type) {
    $properties = array();
    if (isset($this->cardSystems)) {
      foreach ($this->cardSystems as $card_system) {
        if (is_array($card_system->{$type})) {
          $properties = array_merge($properties, $card_system->{$type});
        }
      }
    }
    return array_values(array_unique($properties));
  }

  /**
   * (PHP 5 >= 5.4.0)
   * Specify data which should be serialized to JSON
   * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
   * @return mixed data which can be serialized by json_encode,
   * which is a value of any type other than a resource.
   */
  public function jsonSerialize() {
    $counterEmployee = (array) $this;

    $counterEmployee['permissions'] = $this->getPermissionsFromCardSystems();
    $counterEmployee['groups'] = $this->getGroupsFromCardSystems();

    return $counterEmployee;
  }

}
