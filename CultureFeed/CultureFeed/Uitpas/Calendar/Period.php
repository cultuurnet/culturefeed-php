<?php


class CultureFeed_Uitpas_Calendar_Period {

  /**
   * The start date of the period
   *
   * @var integer
   */
  public $datefrom;

  /**
   * The end date of the period
   *
   * @var integer
   */
  public $dateto;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_Calendar_Period
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object)
  {
    $period = new self();

    $period->datefrom = $object->xpath_time('startDate');
    $period->dateto = $object->xpath_time('endDate');

    return $period;
  }
}
