<?php
/**
 * @file
 */

class CultureFeed_Uitpas_EndDateCalculator_BasedOnRegistrationDate extends CultureFeed_Uitpas_EndDateCalculator_Base {
  /**
   * @param CultureFeed_Uitpas_Passholder $passholder
   * @return CultureFeed_Uitpas_EndDate
   */
  public function endDate(CultureFeed_Uitpas_Passholder $passholder) {
    $now = new DateTime();
    return new CultureFeed_Uitpas_EndDate(
      $now->modify("+ {$this->association->enddateCalculationValidityTime} years")
    );
  }
}
