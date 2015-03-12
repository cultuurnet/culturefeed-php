<?php
/**
 * @file
 */

/**
 * A single action in a ExecuteEventActionsResult response.
 */
class Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction {

  const TYPE_CHECKIN = 'CHECKIN';
  const TYPE_BUYTICKET = 'BUYTICKET';
  const TYPE_CASHIN_POINTSPROMOTION = 'CASHIN_POINTSPROMOTION';
  const TYPE_CASHIN_WELCOMEADVANTAGE = 'CASHIN_WELCOMEADVANTAGE';

  /**
   * @var string
   */
  public $actionType;

  /**
   * @var CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_WelcomeAdvantageResponse|null
   */
  public $welcomeAdvantageResponse;

  /**
   * @var CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_PointsPromotionsResponse|null
   */
  public $pointsPromotionsResponse;

  /**
   * @var
   */
  public $buyTicketResponse;

  /**
   * @var CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_CheckinResponse|null
   */
  public $checkinResponse;

  /**
   * @param CultureFeed_SimpleXMLElement $xml
   * @return Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $action = new self();

    $action->actionType = $xml->xpath_str('actionType');

    switch ($action->actionType) {
      case self::TYPE_CHECKIN:
        $action->checkinResponse = CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_CheckinResponse::createFromXML(
          $xml->xpath('checkinResponse', false)
        );
        break;

      case self::TYPE_BUYTICKET:

        break;

      case self::TYPE_CASHIN_POINTSPROMOTION:
        $action->pointsPromotionsResponse = CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_PointsPromotionsResponse::createFromXML(
          $xml->xpath('pointsPromotionsResponse', false)
        );
        break;

      case self::TYPE_CASHIN_WELCOMEADVANTAGE:
        $action->welcomeAdvantageResponse = CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_WelcomeAdvantageResponse::createFromXML(
          $xml->xpath('welcomeAdvantageResponse', false)
        );
        break;
    }

    return $action;
  }
}
