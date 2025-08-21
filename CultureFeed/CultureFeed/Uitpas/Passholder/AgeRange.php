<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_AgeRange {
  public ?int $ageFrom = null;
  public ?int $ageTo = null;

  /**
   * Checks if an age (in years) is in the range.
   *
   * @param int $age
   */
  public function inRange($age) {
    $lowerApplies = !isset($this->ageFrom) || $age >= $this->ageFrom;
    $upperApplies = !isset($this->ageTo) || $age <= $this->ageTo;

    return $lowerApplies && $upperApplies;
  }
}