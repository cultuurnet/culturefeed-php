<?php
/**
 * @file
 */

/**
 * Response of a POST request to {prefix}/uitpas/passholder/eventActions.
 */
class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult {

  /**
   * @var CultureFeed_Uitpas_Passholder
   */
  public $passholder;

  /**
   * @var Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction[]
   */
  public $actions;

  /**
   * @param CultureFeed_SimpleXMLElement $xml
   * @return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $result = new self();

    $result->passholder = CultureFeed_Uitpas_Passholder::createFromXML(
      $xml->xpath('passHolder', false)
    );

    foreach ($xml->xpath('actions/action') as $action) {
      $result->addActionFromXML($action);
    }

    return $result;
  }

  /**
   * @param CultureFeed_SimpleXMLElement $xml
   * @return void
   */
  private function addActionFromXML(CultureFeed_SimpleXMLElement $xml) {
    $this->actions[] =
      Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction::createFromXML($xml);
  }
}
