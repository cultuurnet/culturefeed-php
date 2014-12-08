<?php

class CultureFeed_Uitpas_Passholder_Membership extends CultureFeed_Uitpas_ValueObject {

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
  
  protected function manipulatePostData(&$data) {
    
    if (isset($data['endDate'])) {
      $data['endDate'] = date('Y-m-d', $data['endDate']);
    }
  }
  
  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {

    $membership = new CultureFeed_Uitpas_Passholder_Membership();
    $membership->association = CultureFeed_Uitpas_Association::createFromXML($object->xpath('association', false));
    $membership->associationId = $object->xpath_str('association/id');
    $membership->name = $object->xpath_str('association/name');
    $membership->endDate = $object->xpath_time('endDate');

    return $membership;
  }

}
