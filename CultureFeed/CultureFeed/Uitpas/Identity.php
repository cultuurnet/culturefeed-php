<?php

final class CultureFeed_Uitpas_Identity extends CultureFeed_Uitpas_ValueObject {
  /**
   * @var \CultureFeed_Uitpas_Passholder_Card
   */
  public $card;

  public ?CultureFeed_Uitpas_Passholder $passHolder = null;

  /**
   * @var \CultureFeed_Uitpas_GroupPass
   */
  public $groupPass;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return self
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $identity = new static();

    $cardXml = $object->xpath('card', false);
    $identity->card = CultureFeed_Uitpas_Passholder_Card::createFromXml($cardXml);

    $passHolderXml = $object->xpath('passHolder', false);
    if ($passHolderXml instanceof CultureFeed_SimpleXMLElement) {
      $identity->passHolder = CultureFeed_Uitpas_Passholder::createFromXML($passHolderXml);
    }

    $groupPassXml = $object->xpath('groupPass', false);
    if ($groupPassXml instanceof CultureFeed_SimpleXMLElement) {
        $identity->groupPass = CultureFeed_Uitpas_GroupPass::createFromXml($groupPassXml);
    }

    return $identity;
  }
}
