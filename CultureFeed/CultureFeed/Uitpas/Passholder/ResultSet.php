<?php

class CultureFeed_Uitpas_Passholder_ResultSet extends CultureFeed_ResultSet
{
  /**
   * Invalid uitpas numbers that were explicitly searched for.
   *
   * @var array
   */
  public $invalidUitpasNumbers;

  /**
   * Constructor for a new CultureFeed_ResultSet instance.
   *
   * @param integer $total
   *   The total number of objects in the complete set.
   * @param array $objects
   *   The objects in the slice.
   * @param array $invalidUitpasNumbers
   *   Any invalid uitpas numbers that were explicitly searched for.
   */
  public function __construct($total = 0, $objects = array(), $invalidUitpasNumbers = array()) {
    parent::__construct($total, $objects);
    $this->invalidUitpasNumbers = $invalidUitpasNumbers;
  }
}
