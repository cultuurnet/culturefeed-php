<?php

abstract class CultureFeed_Uitpas_ValueObject {

  public function toPostData() {
    $data = get_object_vars($this);
    $this->manipulatePostData($data);
    $data = array_filter($data, function($var) {
      return is_array($var) ? !empty($var) : strlen($var);
    });

    return $data;
  }

  protected function manipulatePostData(&$data) {

  }

}
