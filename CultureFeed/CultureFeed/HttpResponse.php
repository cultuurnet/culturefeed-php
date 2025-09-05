<?php

class CultureFeed_HttpResponse {

  public int $code;

  public string $response;

  const ERROR_CODE_ACCESS_DENIED = 'ACCESS_DENIED';

  public function __construct(int $code, string $response) {
    $this->code = $code;
    $this->response = $response;
  }

  public function getStatusCode(): int
  {
    return $this->code;
  }

}
