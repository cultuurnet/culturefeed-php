<?php
/**
 * @file
 */

interface CultureFeed_Uitpas_EndDateCalculator
{
  /**
   * @param CultureFeed_Uitpas_Passholder $passholder
   * @return CultureFeed_Uitpas_EndDate
   */
  public function endDate(CultureFeed_Uitpas_Passholder $passholder);
}
