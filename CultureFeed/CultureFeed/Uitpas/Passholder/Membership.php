<?php

class CultureFeed_Uitpas_Passholder_Membership extends CultureFeed_Uitpas_ValueObject {

  /**
   * ID of the membership.
   *
   * @var string
   */
  public $id;

  /**
   * The id of the association the passholder is linked
   *
   * @var string
   * @deprecated Use $assocation instead.
   */
  public $associationId;
  
  /**
   * The name of the association the passholder is linked
   *
   * @var string
   * @deprecated Use $assocation instead.
   */
  public $name; 

  /**
   * The membership's organization end date. (Required)
   *
   * @var integer
   */
  public $endDate;

  /**
   * Association the membership refers to.
   *
   * @var CultureFeed_Uitpas_Association
   */
  public $association;

  /**
   * @var boolean
   */
  public $renewable;

  /**
   * @var int
   */
  public $renewDate;

  /**
   * @var int
   */
  public $newEndDate;

  /**
   * @var bool
   */
  public $expired;
  
  protected function manipulatePostData(&$data) {
    
    if (isset($data['endDate'])) {
      $data['endDate'] = date('Y-m-d', $data['endDate']);
    }
  }
  
  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {

    $membership = new CultureFeed_Uitpas_Passholder_Membership();
    $membership->id = $object->xpath_str('id');
    $membership->association = CultureFeed_Uitpas_Association::createFromXML($object->xpath('association', false));
    $membership->associationId = $object->xpath_str('association/id');
    $membership->name = $object->xpath_str('association/name');
    $membership->endDate = $object->xpath_time('endDate');
    $membership->renewable = $object->xpath_bool('renewable');
    $membership->newEndDate = $object->xpath_time('newEndDate');
    $membership->renewDate = $object->xpath_time('renewDate');
    $membership->expired = $object->xpath_bool('expired');

    return $membership;
  }

}
