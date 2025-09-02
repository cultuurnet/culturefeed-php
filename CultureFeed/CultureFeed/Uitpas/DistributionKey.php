<?php

class CultureFeed_Uitpas_DistributionKey {

  public string $id;

  public string $name;

  /**
   * @var CultureFeed_Uitpas_DistributionKey_Condition[]
   */
  public array $conditions = array();

  /**
   * @var CultureFeed_Uitpas_Event_PriceClass[]
   */
  public array $priceClasses;

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

  public bool $sameRegion;

  public CultureFeed_Uitpas_CardSystem $cardSystem;

  public static function createFromXML(CultureFeed_SimpleXMLElement $object): CultureFeed_Uitpas_DistributionKey
  {

    $distribution_key = new CultureFeed_Uitpas_DistributionKey();

    $distribution_key->id = $object->xpath_int('id');
    $distribution_key->name = $object->xpath_str('name');
    $distribution_key->conditions = array();
    foreach ($object->xpath('conditions/condition') as $condition) {
      $distribution_key->conditions[] = CultureFeed_Uitpas_DistributionKey_Condition::createFromXML($condition);
    }
    foreach ($object->xpath('priceClasses/priceClass') as $priceClass) {
      $distribution_key->priceClasses[] = CultureFeed_Uitpas_Event_PriceClass::createFromXML($priceClass);
    }
    $distribution_key->tariff = $object->xpath_str('tariff');
    $distribution_key->automatic = $object->xpath_bool('automatic');

    $sameRegionValue = $object->xpath_bool('sameRegion');
    if ($sameRegionValue !== null) {
      $distribution_key->sameRegion = $sameRegionValue;
    }

    return $distribution_key;
  }
}
