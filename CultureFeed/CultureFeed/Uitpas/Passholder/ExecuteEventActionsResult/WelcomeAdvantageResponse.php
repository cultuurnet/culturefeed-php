<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_WelcomeAdvantageResponse
{
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

}
