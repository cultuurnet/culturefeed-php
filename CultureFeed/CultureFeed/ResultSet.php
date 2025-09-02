<?php

class CultureFeed_ResultSet {

  public int $total;

  public array $objects;

  public function __construct(int $total = 0, array $objects = array()) {
    $this->total = $total;
    $this->objects = $objects;
  }
}
