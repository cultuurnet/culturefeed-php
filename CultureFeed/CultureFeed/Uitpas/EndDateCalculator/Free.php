<?php
/**
 * @file
 */

class CultureFeed_Uitpas_EndDateCalcultor_Free extends CultureFeed_Uitpas_EndDateCalculator_Base
{
  public function endDate(
    CultureFeed_Uitpas_Passholder $passholder
  ) {
    return new CultureFeed_Uitpas_EndDate(
      new DateTime('@' . $this->association->enddateCalculationFreeDate),
      FALSE
    );
  }

}
