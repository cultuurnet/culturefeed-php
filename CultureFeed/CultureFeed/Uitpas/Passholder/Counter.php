<?php

class CultureFeed_Uitpas_Passholder_Counter extends CultureFeed_Uitpas_ValueObject {

  /**
   * The ID of the counter
   *
   * @var string
   */
  public $id;

  /**
   * The name of the counter
   *
   * @var string
   */
  public $name;

  /**
   * @param string|null $id
   * @param string|null $name
   */
  public function __construct($id = NULL, $name = NULL) {
    $this->id = $id;
    $this->name = $name;
  }

  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $counter = new CultureFeed_Uitpas_Passholder_Counter();
    $counter->id = $object->xpath_str('id');
    $counter->name = $object->xpath_str('name');

    return $counter;
  }

}
