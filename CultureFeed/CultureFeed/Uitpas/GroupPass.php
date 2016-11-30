<?php
/**
 * @file
 */

class CultureFeed_Uitpas_GroupPass
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $endDate;

    /**
     * @var int
     */
    public $ticketsPerYear;

    /**
     * @var string
     */
    public $uuid;

    /**
     * @var int
     */
    public $availableTickets;

    /**
     * @var boolean
     */
    public $kansenStatuut;

    /**
     * @var array
     */
    public $ticketSaleCoupons = array();

    /**
     * @param CultureFeed_SimpleXMLElement $object
     * @return CultureFeed_Uitpas_GroupPass
     */
    public static function createFromXML(CultureFeed_SimpleXMLElement $object)
    {
        $pass = new self();

        $pass->name = $object->xpath_str('name');
        $pass->endDate = $object->xpath_str('endDate');
        $pass->ticketsPerYear = $object->xpath_int('ticketsPerYear');
        $pass->uuid = $object->xpath_str('uuid');
        $pass->availableTickets = $object->xpath_int('availableTickets');
        $pass->kansenStatuut = $object->xpath_bool('kansenStatuut');

        if (!empty($object->ticketSaleCoupons)) {
          foreach ($object->ticketSaleCoupons as $coupon)
          {
            $pass->ticketSaleCoupons[] = CultureFeed_Uitpas_SaleCoupon::createFromXML($coupon->ticketSaleCoupon);
          }
        }

        return $pass;
    }
}
