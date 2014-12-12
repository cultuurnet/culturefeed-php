<?php
/**
 * @file
 */

abstract class CultureFeed_Uitpas_EndDateCalculator_Base  implements CultureFeed_Uitpas_EndDateCalculator
{
  /**
   * @var CultureFeed_Uitpas_Association
   */
  protected $association;

  public function __construct(CultureFeed_Uitpas_Association $association) {
    $this->association = $association;
  }
}
