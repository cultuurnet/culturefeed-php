<?php
/**
 * @file
 */

class CultureFeed_Uitpas_EndDateCalculatorFactory
{
  private function __construct() {

  }

  public static function createEndDateCalculator(CultureFeed_Uitpas_Association $association) {
    switch ($association->enddateCalculation) {
      case CultureFeed_Uitpas_EndDateCalculation::FREE:
        return new CultureFeed_Uitpas_EndDateCalcultor_Free($association);
        break;

      case CultureFeed_Uitpas_EndDateCalculation::BASED_ON_DATE_OF_BIRTH:
        return new CultureFeed_Uitpas_EndDateCalculator_BasedOnDateOfBirth($association);
        break;

      case CultureFeed_Uitpas_EndDateCalculation::BASED_ON_REGISTRATION_DATE:
        return new CultureFeed_Uitpas_EndDateCalculator_BasedOnRegistrationDate($association);
        break;
    }
  }
}
