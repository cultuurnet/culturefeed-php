<?php

class CultureFeed_HttpException extends Exception {
    function __construct(string $body, int $code) {
    parent::__construct('The reponse for the HTTP request was not 200. ' . $body, $code);
  }
}