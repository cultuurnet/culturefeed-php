<?php
/**
 * @file
 */

final class CultureFeed_Response {

  /**
   * @var string
   */
  private $resource;

  /**
   * @var string
   */
  private $code;

  /**
   * @var string
   */
  private $message;

  private function __construct($code, $message) {
    $this->setCode($code);
    $this->setMessage($message);
  }

  private function setMessage($message) {
    if (!is_string($message) || '' === trim($message)) {
      throw new InvalidArgumentException('Invalid value for message');
    }
    $this->message = $message;
  }

  private function setCode($code) {
    if (!is_string($code) || '' === trim($code)) {
      throw new InvalidArgumentException('Invalid value for code');
    }
    $this->code = $code;
  }

  private function setResource($resource) {
    $this->resource = $resource;
  }

  /**
   * @return string
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * @return string
   */
  public function getResource() {
    return $this->resource;
  }

  /**
   * @param string $xml
   * @return static
   */
  public static function createFromResponseBody($xml) {
    $simpleXml = new SimpleXMLElement($xml);

    $response = new static(
      (string)$simpleXml->code,
      (string)$simpleXml->message
    );

    if ('' !== (string)$simpleXml->resource) {
      $response->setResource(
        (string)$simpleXml->resource
      );
    }

    return $response;
  }
}
