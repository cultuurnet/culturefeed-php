<?php
/**
 * @file
 */

/**
 * Class CultureFeed_Uitpas_EndDateCalculation
 *
 * Enum for EndDateCalculation types.
 */
class CultureFeed_Uitpas_EndDateCalculation {

  const FREE = 'FREE';
  const BASED_ON_DATE_OF_BIRTH = 'BASED_ON_DATE_OF_BIRTH';
  const BASED_ON_REGISTRATION_DATE = 'BASED_ON_REGISTRATION_DATE';

  // Intentially made private because this is an ENUM, it should not be
  // instantiated.
  private function __construct() {
  }

} 
