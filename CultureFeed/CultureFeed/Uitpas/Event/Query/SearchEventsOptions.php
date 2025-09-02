<?php

class CultureFeed_Uitpas_Event_Query_SearchEventsOptions extends CultureFeed_Uitpas_ValueObject {

  const SORT_CREATION_DATE = "CREATION_DATE";
  const SORT_TITLE = "TITLE";
  const SORT_CASHING_PERIOD_END = "CASHING_PERIOD_END";
  const SORT_POINTS = "POINTS";

  const ORDER_ASC = "ASC";
  const ORDER_DESC = "DESC";

  public string $balieConsumerKey;

  /**
   * @var integer|string
   */
  public $startDate;

  /**
   * @var integer|string
   */
  public $endDate;

  public string $locatieId;

  public string $inrichterId;

  public string $city;

  public string $sortField;

  public string $sortOrder = self::ORDER_DESC;

  public int $max = 20;

  public int $start = 0;

  public string $uitpasNumber;

  public string $q;

  public string $cdbid;

  public string $datetype;

  public string $sort;

  public bool $description = true;


  protected function manipulatePostData(&$data): void {
    if (isset($data['startDate']) && is_integer($data['startDate'])) {
      $data['startDate'] = date(DateTime::W3C, $data['startDate']);
    }

    if (isset($data['endDate']) && is_integer($data['endDate'])) {
      $data['endDate'] = date(DateTime::W3C, $data['endDate']);
    }

    if (isset($data['basicSearch']) && $data['basicSearch']) {
      $data['basicSearch'] = 'true';
    }
    if (isset($data['basicSearch']) && ! $data['basicSearch']) {
      $data['basicSearch'] = 'false';
    }

  }

  public function readValues($values): void {
    foreach($values as $k => $v) {
      $this->$k = $v;
    }

    if (preg_match( "/^[0-9[0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/", $this->startDate)) {
      $this->startDate = strtotime($this->startDate);
    }

    if (preg_match("/^[0-9[0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/", $this->endDate)) {
      $this->endDate = strtotime($this->endDate);
    }
  }

   public function readQueryString( $str ): void {
     $values = array();
     parse_str(urldecode($str) , $values);
     $this->readValues( $values );
   }
}
