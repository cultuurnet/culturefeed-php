<?php

/**
 * Class to represent a mailing.
 */
class CultureFeed_Mailing {

  /**
   * ID of the mailing object.
   * @var string
   */
  public $id;

  /**
   * Name of the mailing object.
   * @var string
   */
  public $name;

  /**
   * The template associated with the mailing.
   * @var CultureFeed_Template
   */
  public $template;

  /**
   * Description of the mailing.
   * @var string
   */
  public $description;

  /**
   * Id of the service consumer.
   * @var string
   */
  public $consumerKey;

  /**
   * Boolean indicating wether the mail is sent periodically.
   * @var bool
   */
  public $enabled;

  /**
   * Convert a CultureFeed_Mailing object to an array that can be used as data in POST requests that expect mailing data.
   *
   * @return array
   *   Associative array representing the object. For documentation of the structure, check the CultureFeed API documentation.
   */
  public function toPostData() {

    // For most properties we can rely on get_object_vars.
    $data = get_object_vars($this);

    // Represent private as a string (true/false);
    $boolean_properties = array(
      'enabled',
    );

    foreach ($boolean_properties as $property) {
      if (isset($data[$property])) {
        $data[$property] = $data[$property] ? 'true' : 'false';
      }
    }

    $data = array_filter($data);

    return $data;
  }

}

