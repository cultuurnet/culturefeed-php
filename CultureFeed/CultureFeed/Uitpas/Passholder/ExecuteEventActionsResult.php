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
  private function addActionFromXML(CultureFeed_SimpleXMLElement $xml): void {
    $this->actions[] =
      Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction::createFromXML($xml);
  }

  /**
   * @return Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction|null
   */
  public function getCheckinAction() {
    foreach ($this->actions as $action) {
      if ($action->actionType == $action::TYPE_CHECKIN) {
        return $action;
      }
    }
    return null;
  }

  /**
   * @param int $id
   * @return Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction|null
   */
  public function getWelcomeAdvantageAction($id) {
    foreach ($this->actions as $action) {
      if ($action->actionType == $action::TYPE_CASHIN_WELCOMEADVANTAGE &&
        $action->welcomeAdvantageResponse->promotion->id == $id) {
        return $action;
      }
    }
    return null;
  }

  /**
   * @param $id
   * @return Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction|null
   */
  public function getPointsPromotionAction($id) {
    foreach ($this->actions as $action) {
      if ($action->actionType == $action::TYPE_CASHIN_POINTSPROMOTION &&
          $action->pointsPromotionsResponse->promotion->id == $id) {
        return $action;
      }
    }
    return null;
  }

  /**
   * @return Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction|null
   */
  public function getBuyTicketAction() {
    foreach ($this->actions as $action) {
      if ($action->actionType == $action::TYPE_BUYTICKET) {
        return $action;
      }
    }
    return null;
  }
}
