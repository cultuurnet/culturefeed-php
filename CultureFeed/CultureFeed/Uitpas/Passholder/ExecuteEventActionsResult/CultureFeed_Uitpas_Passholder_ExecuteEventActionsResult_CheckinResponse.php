<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_CheckinResponse {

  /**
   * @var string
   */
  public $code;

  /**
   * @var int
   */
  public $points;

  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $response = new self();

    $response->code = $xml->xpath_str('code');
    $response->points = $xml->xpath_int('points');

    return $response;
  }
}
