<?php
/**
 * @file
 */

class CultureFeed_Uitpas_GroupPass
{
    /**
     * @var int
     */
    public $availableTickets;

    /**
     * @var string
     */
    public $name;

    /**
     * @param CultureFeed_SimpleXMLElement $object
     * @return CultureFeed_Uitpas_GroupPass
     */
    public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
        $pass = new self();

        $pass->name = $object->xpath_str('name');
        $pass->availableTickets = $object->xpath_int('availableTickets');

        return $pass;
    }
}
