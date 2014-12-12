<?php
/**
 * @file
 */

class CultureFeed_Uitpas_EndDate {

  /**
   * @var bool
   */
  private $fixed;

  /**
   * @var DateTime
   */
  private $date;

  public function __construct(DateTime $date = NULL, $isFixed = TRUE) {
    $this->date = $date;
    $this->fixed = $isFixed;
  }

  /**
   * @return DateTime
   */
  public function getDate() {
    return $this->date;
  }

  /**
   * @return boolean
   */
  public function isFixed() {
    return $this->fixed;
  }
}
