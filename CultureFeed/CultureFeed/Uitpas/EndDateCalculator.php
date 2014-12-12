<?php
/**
 * @file
 */

interface CultureFeed_Uitpas_EndDateCalculator
{
  /**
   * @param CultureFeed_Uitpas_Passholder $passholder
   * @return DateTime
   */
  public function endDate(CultureFeed_Uitpas_Passholder $passholder);
}
