<?php
/**
 * @file
 */

class CultureFeed_Uitpas_EndDateCalculator_BasedOnDateOfBirth extends CultureFeed_Uitpas_EndDateCalculator_Base {
  /**
   * @param CultureFeed_Uitpas_Passholder $passholder
   * @return CultureFeed_Uitpas_EndDate
   */
  public function endDate(CultureFeed_Uitpas_Passholder $passholder) {
    $endDate = null;
    if ($passholder->dateOfBirth) {
      $endDate = new DateTime('@' . $passholder->dateOfBirth);
      $endDate->modify(
        "+ {$this->association->enddateCalculationValidityTime} years"
      );
    }

    return new CultureFeed_Uitpas_EndDate($endDate);
  }


}
