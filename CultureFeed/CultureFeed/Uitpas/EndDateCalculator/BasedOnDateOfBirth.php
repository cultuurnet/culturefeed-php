<?php
/**
 * @file
 */

class CultureFeed_Uitpas_EndDateCalculator_BasedOnDateOfBirth extends CultureFeed_Uitpas_EndDateCalculator_Base {
  /**
   * @param CultureFeed_Uitpas_Passholder $passholder
   * @return DateTime
   */
  public function endDate(CultureFeed_Uitpas_Passholder $passholder) {
    $dateOfBirth = new DateTime('@' . $passholder->dateOfBirth);

    $dateOfBirth->modify("+ {$this->association->enddateCalculationValidityTime} years");
  }


}
