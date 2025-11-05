<?php

class CultureFeed_Uitpas_Response {

  /**
   * The response code
   *
   * @var string
   */
  public $code;

  public ?string $message;

  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $response = new CultureFeed_Uitpas_Response();
    $response->code = $object->xpath_str('code');
    $response->message = $object->xpath_str('message');

    return $response;
  }

}