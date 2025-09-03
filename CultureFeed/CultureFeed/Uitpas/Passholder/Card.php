<?php

class CultureFeed_Uitpas_Passholder_Card extends CultureFeed_Uitpas_ValueObject {

  const STATUS_STOCK = 'STOCK';
  const STATUS_LOCAL_STOCK = 'LOCAL_STOCK';
  const STATUS_ACTIVE = 'ACTIVE';
  const STATUS_BLOCKED = 'BLOCKED';

  public ?string $city = null;

  public string $uitpasNumber;

  /**
   * True if the passholder has a kansenpas
   *
   * @var boolean
   */
  public $kansenpas;

  /**
   * Current status of the uitpas
   *
   * @var string
   */
  public $status;

  /**
   * The actual type of this card
   * types discovered so far: CARD, STICKER and KEY
   *
   * @var string
   */
  public $type;

  /**
   * CardSystem the card belongs to.
   *
   * @var \CultureFeed_Uitpas_CardSystem
   */
  public $cardSystem;

  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $card = new CultureFeed_Uitpas_Passholder_Card();
    $card->city = $object->xpath_str('city');
    $card->uitpasNumber = $object->xpath_str('uitpasNumber/uitpasNumber');
    $card->kansenpas = $object->xpath_bool('kansenpas');
    $card->status = $object->xpath_str('status');
    $card->type = $object->xpath_str('cardType');

    $cardSystemXml = $object->xpath('cardSystem', false);
    if ($cardSystemXml instanceof CultureFeed_SimpleXMLElement) {
      $card->cardSystem = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    } elseif (!is_null($object->xpath_int('cardSystemId'))) {
      $card->cardSystem = new CultureFeed_Uitpas_CardSystem(
          $object->xpath_int('cardSystemId'),
        ''
      );
    }

    return $card;
  }

}
