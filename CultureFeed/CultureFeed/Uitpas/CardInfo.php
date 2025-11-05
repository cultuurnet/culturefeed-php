<?php

final class CultureFeed_Uitpas_CardInfo {

  /**
   * @var CultureFeed_Uitpas_CardSystem
   */
  public $cardSystem;

  /**
   * @var string
   */
  public $uitpasNumber;

  /**
   * @var string
   */
  public $status;

  /**
   * @var string
   */
  public $type;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   *
   * @return self
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $instance = new static();

    $instance->cardSystem = CultureFeed_Uitpas_CardSystem::createFromXML($object->xpath('cardSystem', FALSE));
    $instance->uitpasNumber = $object->xpath_str('uitpasNumber');
    $instance->status = $object->xpath_str('status');
    $instance->type = $object->xpath_str('cardType');

    return $instance;
  }

  /**
   * @return bool
   */
  public function kansenStatuut() {
    return '1' == substr($this->uitpasNumber, -2, 1);
  }
}
