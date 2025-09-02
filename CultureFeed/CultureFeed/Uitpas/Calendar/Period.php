<?php


class CultureFeed_Uitpas_Calendar_Period {

  public int $datefrom;

  public ?int $dateto = null;

  public function __construct($datefrom, $dateto = null)
  {
    $this->datefrom = $datefrom;
    $this->dateto = $dateto;
  }

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_Calendar_Period
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object)
  {
      return new self(
          $object->xpath_time('startDate'),
          $object->xpath_time('endDate')
      );
  }
}
