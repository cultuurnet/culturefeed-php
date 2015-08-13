<?php

class CultureFeed_Uitpas_Event_PriceClass extends CultureFeed_Uitpas_ValueObject {
  /**
   * @var string
   */
  public $name;

  /**
   * @var float
   */
  public $price;

  /**
   * @var float|null
   */
  public $tariff;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_Event_PriceClass
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $priceClass = new CultureFeed_Uitpas_Event_PriceClass();

    $priceClass->name = $object->xpath_str('name', FALSE);
    $priceClass->price = $object->xpath_float('price', FALSE);
    $priceClass->tariff = $object->xpath_float('tariff', FALSE);

    return $priceClass;
  }
}
