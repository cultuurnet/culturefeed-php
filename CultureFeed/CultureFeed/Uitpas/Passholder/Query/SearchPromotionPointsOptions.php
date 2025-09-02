<?php

class CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions extends CultureFeed_Uitpas_ValueObject {

  const SORT_CREATION_DATE = "CREATION_DATE";
  const SORT_TITLE = "TITLE";
  const SORT_CASHING_PERIOD_END = "CASHING_PERIOD_END";
  const SORT_POINTS = "POINTS";

  const ORDER_ASC = "ASC";
  const ORDER_DESC = "DESC";

  /**
   * @deprecated Use one of the CultureFeed_Uitpas_Passholder_PointsPromotion::CASHIN_... constants instead.
   */
  const FILTER_POSSIBLE = "POSSIBLE";

  public string $city;

  public int $minPoints;

  public int $maxPoints;

  public string $balieConsumerKey;

  public int $cashingPeriodBegin;

  public int $cashingPeriodEnd;

  public int $grantingPeriodBegin;

  public int $grantingPeriodEnd;

  public string $sort = self::SORT_CREATION_DATE;

  public string $order = self::ORDER_DESC;

  public int $max = 20;

  public int $start = 0;

  public string $uid;

  public string $uitpasNumber;

  public bool $filterOnUserPoints = false;

  public bool $published = false;

  public int $simulatedExtraPoints;

  public bool $unexpired = false;

  public string $cashInState = self::FILTER_POSSIBLE;

  public bool $inSpotlight;

  public string $owningCardSystemId;

  public string $orderByOwningCardSystemId;

  public string $applicableCardSystemId;


  protected function manipulatePostData(&$data): void {
    if (isset($data['inSpotlight'])) {
      $data['inSpotlight'] = $data['inSpotlight'] ? "true" : "false";
    }

    if (isset($data['unexpired'])) {
      $data['unexpired'] = $data['unexpired'] ? "true" : "false";
    }

    if (isset($data['published'])) {
      $data['published'] = $data['published'] ? "true" : "false";
    }

    if (isset($data['cashingPeriodBegin'])) {
      $data['cashingPeriodBegin'] = date('c', $data['cashingPeriodBegin']);
    }

    if (isset($data['cashingPeriodEnd'])) {
      $data['cashingPeriodEnd'] = date('c', $data['cashingPeriodEnd']);
    }

    if (isset($data['filterOnUserPoints'])) {
      $data['filterOnUserPoints'] = $data['filterOnUserPoints'] ? "true" : "false";
    }

    if (isset($data['grantingPeriodBegin'])) {
      $data['grantingPeriodBegin'] = date('c', $data['grantingPeriodBegin']);
    }

    if (isset($data['grantingPeriodEnd'])) {
      $data['grantingPeriodEnd'] = date('c', $data['grantingPeriodEnd']);
    }
  }
}
