<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_CheckinResponse implements CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_ResultInterface {

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
