<?php

class CultureFeed_Uitpas_Identity extends CultureFeed_Uitpas_ValueObject {
  /**
   * @var \CultureFeed_Uitpas_CardInfo
   */
  public $card;

  /**
   * @var \CultureFeed_Uitpas_Passholder
   */
  public $passHolder;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return self
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $identity = new static();

    $cardXml = $object->xpath('card', false);
    $identity->card = CultureFeed_Uitpas_CardInfo::createFromXml($cardXml);

    $passHolderXml = $object->xpath('passHolder', false);
    if ($passHolderXml instanceof CultureFeed_SimpleXMLElement) {
      $identity->passHolder = CultureFeed_Uitpas_Passholder::createFromXML($passHolderXml);
    }

    return $identity;
  }
}
