<?php

class CultureFeed_Uitpas_Event_TicketSale_Coupon extends CultureFeed_Uitpas_ValueObject {
  /**
   * @var string
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $exchangeConstraint;

  /**
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $buyConstraint;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_Event_TicketSale_Coupon
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object)
  {
    $coupon = new CultureFeed_Uitpas_Event_TicketSale_Coupon();

    $coupon->id = $object->xpath_str('id', FALSE);
    $coupon->name = $object->xpath_str('name', FALSE);

    $exchangeConstraintElement = $object->xpath('exchangeConstraint', FALSE);
    if ($exchangeConstraintElement instanceof CultureFeed_SimpleXMLElement) {
      $coupon->exchangeConstraint = CultureFeed_Uitpas_PeriodConstraint::createFromXML($exchangeConstraintElement);
    }

    $buyConstraintElement = $object->xpath('buyConstraint', FALSE);
    if ($buyConstraintElement instanceof CultureFeed_SimpleXMLElement) {
      $coupon->buyConstraint = CultureFeed_Uitpas_PeriodConstraint::createFromXML($buyConstraintElement);
    }

    return $coupon;
  }
}
