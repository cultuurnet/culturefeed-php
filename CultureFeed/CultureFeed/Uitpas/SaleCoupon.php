<?php
/**
 * @file
 */

class CultureFeed_Uitpas_SaleCoupon
{
    /**
     * @var array
     */
    public $buyConstraint = array();

    /**
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public $exchangeConstraint = array();

    /**
     * @var boolean
     */
    public $expired;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $remainingTotal = array();

    /**
     * @param CultureFeed_SimpleXMLElement $object
     * @return CultureFeed_Uitpas_SaleCoupon
     */
    public static function createFromXML(CultureFeed_SimpleXMLElement $object)
    {
        $coupon = new self();

        $coupon->buyConstraint['periodType'] = $object->buyConstraint->xpath_str('periodType');
        $coupon->buyConstraint['periodVolume'] = $object->buyConstraint->xpath_int('periodVolume');
        $coupon->description = $object->xpath_str('description');
        $coupon->exchangeConstraint['periodType'] = $object->exchangeConstraint->xpath_str('periodType');
        $coupon->exchangeConstraint['periodVolume'] = $object->exchangeConstraint->xpath_int('periodVolume');
        $coupon->expired = $object->xpath_str('expired');
        $coupon->id = $object->xpath_str('id');
        $coupon->name = $object->xpath_str('name');
        $coupon->remainingTotal['periodType'] = $object->remainingTotal->xpath_str('periodType');
        $coupon->remainingTotal['periodVolume'] = $object->remainingTotal->xpath_int('periodVolume');


        return $coupon;
    }
}
