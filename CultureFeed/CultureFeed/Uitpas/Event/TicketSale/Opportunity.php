<?php

class CultureFeed_Uitpas_Event_TicketSale_Opportunity extends CultureFeed_Uitpas_ValueObject {
  const TYPE_DEFAULT = 'DEFAULT';
  const TYPE_COUPON = 'COUPON';

  const BUY_CONSTRAINT_INVALID_CARD = 'INVALID_CARD';
  const BUY_CONSTRAINT_INVALID_CARD_STATUS = 'INVALID_CARD_STATUS';
  const BUY_CONSTRAINT_KANSENSTATUUT_EXPIRED = 'KANSENSTATUUT_EXPIRED';
  const BUY_CONSTRAINT_MAXIMUM_REACHED = 'MAXIMUM_REACHED';
  const BUY_CONSTRAINT_INVALID_DATE_CONSTRAINTS = 'INVALID_DATE_CONSTRAINTS';

  /**
   * Type of the ticket sale.
   *
   * Either DEFAULT or COUPON.
   *
   * @var string
   */
  public $type;

  /**
   * Reason why the ticket is no longer able to be sold.
   *
   * @var string
   */
  public $buyConstraintReason;

  /**
   * @var CultureFeed_Uitpas_Event_PriceClass[]
   */
  public $priceClasses;

  /**
   * @var CultureFeed_Uitpas_Event_TicketSale_Coupon
   */
  public $ticketSaleCoupon;

  /**
   * Amount of sales remaining for coupons.
   *
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $remainingForEvent;

  /**
   * Amount of sales remaining.
   *
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $remainingTotal;

  public function __construct() {
      $this->priceClasses = array();
  }

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_Event_TicketSale_Opportunity
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $opportunity = new CultureFeed_Uitpas_Event_TicketSale_Opportunity();
    $opportunity->type = CultureFeed_Uitpas_Event_TicketSale_Opportunity::TYPE_DEFAULT;

    if (isset($object['type'])) {
      $opportunity->type = (string) $object['type'];
    }

    $opportunity->buyConstraintReason = $object->xpath_str('buyConstraintReason', FALSE);

    foreach ($object->xpath('priceClasses/priceClass') as $priceClass) {
      $opportunity->priceClasses[] = CultureFeed_Uitpas_Event_PriceClass::createFromXml($priceClass);
    }

    $couponElement = $object->xpath('ticketSaleCoupon', FALSE);
    if ($couponElement instanceof CultureFeed_SimpleXMLElement) {
      $opportunity->ticketSaleCoupon = CultureFeed_Uitpas_Event_TicketSale_Coupon::createFromXml($couponElement);
    }

    $remainingForEventElement = $object->xpath('remainingForEvent', FALSE);
    if ($remainingForEventElement instanceof CultureFeed_SimpleXMLElement) {
      $opportunity->remainingForEvent = CultureFeed_Uitpas_PeriodConstraint::createFromXml($remainingForEventElement);
    }

    $remainingTotalElement = $object->xpath('remainingTotal', FALSE);
    if ($remainingTotalElement instanceof CultureFeed_SimpleXMLElement) {
      $opportunity->remainingTotal = CultureFeed_Uitpas_PeriodConstraint::createFromXml($remainingTotalElement);
    }

    return $opportunity;
  }
}
