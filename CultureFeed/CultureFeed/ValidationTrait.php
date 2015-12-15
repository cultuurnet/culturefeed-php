<?php

/**
 * @file
 * Contains \Culturefeed_ValidationTrait.
 */

/**
 * @class
 * Trait with all response validation methods.
 */
trait Culturefeed_ValidationTrait {

  /**
   * Validate the request result.
   *
   * @param string $result
   *   Result from the request.
   * @param string $valid_status_code
   *   Status code if this is a valid request.
   * @param string $status_xml_tag
   *   Xml tag where the status code can be checked.
   * @return CultureFeed_SimpleXMLElement The parsed xml.
   *
   * @throws CultureFeed_ParseException
   *   If the result could not be parsed.
   * @throws CultureFeed_InvalidCodeException
   *   If no valid result status code.
   */
  protected function validateResult($result, $valid_status_code, $status_xml_tag = 'code') {

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $status_code = $xml->xpath_str($status_xml_tag);

    if ($status_code == $valid_status_code) {
      return $xml;
    }

    throw new CultureFeed_InvalidCodeException($xml->xpath_str('message'), $status_code);

  }

}
