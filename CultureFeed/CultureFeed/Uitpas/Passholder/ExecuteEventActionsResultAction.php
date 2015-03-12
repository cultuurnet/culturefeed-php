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
   * @param CultureFeed_SimpleXMLElement $xml
   * @return Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $action = new self();

    $action->actionType = $xml->xpath_str('actionType');

    return $action;
  }
}
