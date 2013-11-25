<?php

class CultureFeed_Uitpas_DistributionKey {

  /**
   * ID of the association.
   *
   * @var int
   */
  public $id;

  /**
   * Name of the association.
   *
   * @var string
   */
  public $name;

  /**
   * Conditions.
   *
   * @var array
   */
  public $conditions;

  /**
   * Tariff of the distributionkey.
   *
   * @var string
   */
  public $tariff;

  /**
   * Automatic.
   *
   * @var boolean
   */
  public $automatic;

  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {

    $distribution_key = new CultureFeed_Uitpas_DistributionKey();

    $distribution_key->id = $object->xpath_int('id');
    $distribution_key->name = $object->xpath_str('name');
    $distribution_key->conditions = array();
    foreach ($object->xpath('conditions/condition') as $condition) {
      $distribution_key->conditions[] = CultureFeed_Uitpas_DistributionKey_Condition::createFromXML($condition, FALSE);
    }
    $distribution_key->tariff = $object->xpath_str('tariff');
    $distribution_key->automatic = $object->xpath_bool('automatic');

    return $distribution_key;
  }

}