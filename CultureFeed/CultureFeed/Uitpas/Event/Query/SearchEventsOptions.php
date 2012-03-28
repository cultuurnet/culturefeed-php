<?php

class CultureFeed_Uitpas_Event_Query_SearchEventsOptions extends CultureFeed_Uitpas_ValueObject {

  const SORT_CREATION_DATE = "CREATION_DATE";
  const SORT_TITLE = "TITLE";
  const SORT_CASHING_PERIOD_END = "CASHING_PERIOD_END";
  const SORT_POINTS = "POINTS";

  const ORDER_ASC = "ASC";
  const ORDER_DESC = "DESC";

  /**
   * Consumer key of the counter
   *
   * @var string
   */
  public $balieConsumerKey;

  /**
   * Begin date
   *
   * @var integer
   */
  public $startDate;

  /**
   * End date
   *
   * @var integer
   */
  public $endDate;

  /**
   * Search for events with a given location ID
   *
   * @var string
   */
  public $locatieId;

  /**
   * Search for events with a given inrichter ID
   *
   * @var string
   */
  public $inrichterId;

  /**
   * Search for events with a given city
   *
   * @var string
   */
  public $city;

  /**
   * Sort field.
   *
   * @var string
   */
  public $sortField = self::SORT_CREATION_DATE;

  /**
   * Sort direction
   *
   * @var string
   */
  public $sortOrder = self::ORDER_DESC;

  /**
   * Maximum number of results. Default: 20
   *
   * @var integer
   */
  public $max = 20;

  /**
   * Results offset. Default: 0
   *
   * @var integer
   */
  public $start = 0;

  /**
   * The Uitpas of the passholder
   *
   * @var string
   */
  public $uitpasNumber;
  
  /**
   * A search term
   *
   * @var string
   */
  public $q;
  
  /**
   * The CDBID of the event
   * 
   * @var string
   */
  public $cdbid;

  protected function manipulatePostData(&$data) {
    if (isset($data['startDate'])) {
      $data['startDate'] = date('Y-m-d', $data['startDate']);
    }

    if (isset($data['endDate'])) {
      $data['endDate'] = date('Y-m-d', $data['endDate']);
    }
  }

}