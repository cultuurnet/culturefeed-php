<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_PointsPromotionsResponse
{
  /**
   * @var string
   */
  public $code;

  /**
   * @var CultureFeed_Uitpas_Passholder_PointsPromotion
   */
  public $promotion;

  /**
   * @var string
   */
  public $cashInState;

  /**
   * @param CultureFeed_SimpleXMLElement $xml
   * @return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_PointsPromotionsResponse
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $response = new self();

    $response->code = $xml->xpath_str('code');

    $response->promotion = CultureFeed_Uitpas_Passholder_PointsPromotion::createFromXML(
      $xml->xpath('promotion', false)
    );

    if ($response->code === 'ACTION_NOT_ALLOWED') {
      $response->cashInState = $xml->xpath_str('cashInState');
    }

    return $response;
  }

}
