<?php
/**
 * @file
 */

/**
 * Data to be passed in a POST request to {prefix}/uitpas/passholder/eventActions.
 */
class CultureFeed_Uitpas_Passholder_Query_ExecuteEventActions extends CultureFeed_Uitpas_ValueObject {

  /**
   * @var string
   */
  protected $uitpasNumber;

  /**
   * @var string|NULL
   */
  public $cdbid;

  /**
   * @var bool
   */
  public $actionCheckin = false;

  /**
   * @var bool
   */
  public $actionBuyTicket = false;

  /**
   * @var string[]
   */
  public $pointsPromotionIds = array();

  /**
   * @var string[]
   */
  public $welcomeAdvantageIds = array();

  /**
   * @var string|NULL
   */
  public $balieConsumerKey;

  /**
   * @param string $uitpasNumber
   * @param string|NULL $cdbid
   * @param string|NULL $balieConsumerKey
   */
  public function __construct($uitpasNumber, $cdbid = NULL, $balieConsumerKey = NULL) {
    $this->uitpasNumber = $uitpasNumber;
    $this->cdbid = $cdbid;
    $this->balieConsumerKey = $balieConsumerKey;
  }

  public function buyTicket() {
    $this->actionBuyTicket = TRUE;
  }

  public function checkin() {
    $this->actionCheckin = TRUE;
  }

  /**
   * @param string $id
   */
  public function collectWelcomeAdvantage($id) {
    $this->welcomeAdvantageIds[] = $id;
  }

  /**
   * @param string $id
   */
  public function collectPointsPromotion($id) {
    $this->pointsPromotionIds[] = $id;
  }

  /**
   * {@inheritdoc}
   */
  protected function manipulatePostData(&$data) {
    if (isset($data['actionCheckin'])) {
      $data['actionCheckin'] = $data['actionCheckin'] ? 'true' : 'false';
    }

    if (isset($data['actionBuyTicket'])) {
      $data['actionBuyTicket'] = $data['actionBuyTicket'] ? 'true' : 'false';
    }

    if (isset($data['pointsPromotionIds'])) {
      $data['pointsPromotionIds'] = implode(',', $data['pointsPromotionIds']);
    }

    if (isset($data['welcomeAdvantageIds'])) {
      $data['welcomeAdvantageIds'] = implode(',', $data['welcomeAdvantageIds']);
    }
  }
}
