<?php
/**
 * @file
 */

/**
 * Response of a POST request to {prefix}/uitpas/passholder/eventActions.
 */
class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult {

  public $passholder;

  /**
   * @param CultureFeed_SimpleXMLElement $xml
   * @return self
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $xml) {
    $result = new self();

    $result->passholder = CultureFeed_Uitpas_Passholder::createFromXML(
      $xml->xpath('passHolder', false)
    );

    return $result;
  }
}
