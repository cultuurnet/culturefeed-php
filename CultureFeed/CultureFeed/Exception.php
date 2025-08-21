<?php

class CultureFeed_Exception extends Exception {
  public $error_code;

  /**
   * @var string
   */
  protected $userFriendlyMessage;

  function __construct($message, $error_code, $code = 0) {
    parent::__construct($message, $code);
    $this->error_code = $error_code;
  }

  /**
   * @return string|null
   */
  public function getUserFriendlyMessage() {
    return $this->userFriendlyMessage;
  }

  public function setUserFriendlyMessage(string $message): void {
    $this->userFriendlyMessage = $message;
  }
}
