<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_WelcomeAdvantageResponse implements CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_ResultInterface {
  /**
   * @var string
   */
  public $code;

  /**
   * @var CultureFeed_Uitpas_Passholder_WelcomeAdvantage
   */
  public $promotion;

  /**
   * @param CultureFeed_SimpleXMLElement $xml
   * @return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_WelcomeAdvantageResponse
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $response = new self();

    $response->code = $xml->xpath_str('code');

    $response->promotion = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML(
      $xml->xpath('promotion', false)
    );

    return $response;
  }

  public function isSuccess() {
    return $this->code === 'ACTION_SUCCEEDED';
  }

  /**
   * @return string
   */
  function getCode() {
    return $this->code;
  }


}
