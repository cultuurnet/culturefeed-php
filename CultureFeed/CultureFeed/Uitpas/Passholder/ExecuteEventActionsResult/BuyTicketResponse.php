<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_BuyTicketResponse implements CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_ResultInterface {

  /**
   * @var string
   */
  public $code;

  /**
   * @var float
   */
  public $price;

  /**
   * @var string
   */
  public $id;

  /**
   * @var float
   */
  public $tariff;

  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $response = new self();

    $response->code = $xml->xpath_str('code');
    $response->price = $xml->xpath_float('price');
    $response->id = $xml->xpath_str('id');
    $response->tariff = $xml->xpath_float('tariff');

    return $response;
  }

  public function isSuccess()
  {
    return $this->code == 'ACTION_SUCCEEDED';
  }

  /**
   * @return string
   */
  function getCode() {
    return $this->code;
  }
}
