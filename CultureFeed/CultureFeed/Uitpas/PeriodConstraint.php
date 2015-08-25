<?php

class CultureFeed_Uitpas_PeriodConstraint extends CultureFeed_Uitpas_ValueObject {
  const TYPE_ABSOLUTE = 'ABSOLUTE';
  const TYPE_DAY = 'DAY';
  const TYPE_WEEK = 'WEEK';
  const TYPE_MONTH = 'MONTH';
  const TYPE_QUARTER = 'QUARTER';
  const TYPE_YEAR = 'YEAR';

  /**
   * @var string
   */
  public $type;

  /**
   * @var int
   */
  public $volume;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_PeriodConstraint
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $periodConstraint = new CultureFeed_Uitpas_PeriodConstraint();

    $periodConstraint->type = $object->xpath_str('periodType', FALSE);
    $periodConstraint->volume = $object->xpath_int('periodVolume', FALSE);

    return $periodConstraint;
  }
}
