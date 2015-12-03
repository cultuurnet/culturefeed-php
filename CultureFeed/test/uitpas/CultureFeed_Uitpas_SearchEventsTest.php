<?php
/**
 * @file
 * PHPUnit test for searching events.
 */

class CultureFeed_Uitpas_SearchEventsTest extends PHPUnit_Framework_TestCase {

  /**
   * Test the searching events.
   */
  public function testSearchEvents() {
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/data/events/searchresults.xml');

    $oauth_client_stub->expects($this->once())
             ->method('consumerGetAsXML')
             ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $query = new CultureFeed_Uitpas_Event_Query_SearchEventsOptions();

    $data = $cf->uitpas()->searchEvents($query);

    $this->assertContainsOnly('CultureFeed_Uitpas_Event_CultureEvent', $data->objects);
    $this->assertCount(7, $data->objects);
    $this->assertEquals("8ea1787b-d08c-4fa8-8d3c-20d1e5cc6e6a", $data->objects[2]->cdbid);

    /* @var CultureFeed_Uitpas_Event_CultureEvent[] $events */
    $events = $data->objects;

    $this->assertCount(2, $events[1]->ticketSales);
    $this->assertCount(1, $events[2]->ticketSales);

    // TicketSale 1 of 2nd event. (Coupon)
    $this->assertInstanceOf(
      'CultureFeed_Uitpas_Event_TicketSale_Opportunity',
      $events[1]->ticketSales[0]
    );
    $this->assertEquals(
      CultureFeed_Uitpas_Event_TicketSale_Opportunity::TYPE_COUPON,
      $events[1]->ticketSales[0]->type
    );

    $this->assertInstanceOf(
      'CultureFeed_Uitpas_Event_TicketSale_Coupon',
      $events[1]->ticketSales[0]->ticketSaleCoupon
    );
    $this->assertEquals(
      18,
      $events[1]->ticketSales[0]->ticketSaleCoupon->id
    );
    $this->assertEquals(
      'TEST-UITPAS1579',
      $events[1]->ticketSales[0]->ticketSaleCoupon->name
    );
    $this->assertPeriodConstraint(
      $events[1]->ticketSales[0]->ticketSaleCoupon->buyConstraint,
      CultureFeed_Uitpas_PeriodConstraint::TYPE_ABSOLUTE,
      6
    );
    $this->assertPeriodConstraint(
      $events[1]->ticketSales[0]->ticketSaleCoupon->exchangeConstraint,
      CultureFeed_Uitpas_PeriodConstraint::TYPE_MONTH,
      3
    );

    $this->assertPeriodConstraint(
      $events[1]->ticketSales[0]->remainingForEvent,
      CultureFeed_Uitpas_PeriodConstraint::TYPE_WEEK,
      10
    );
    $this->assertPeriodConstraint(
      $events[1]->ticketSales[0]->remainingTotal,
      CultureFeed_Uitpas_PeriodConstraint::TYPE_QUARTER,
      50
    );

    $this->assertPriceClass(
      $events[1]->ticketSales[0]->priceClasses[0],
      'Default prijsklasse',
      27.0,
      13.5
    );

    // TicketSale 2 of 2nd event. (Kansentarief with buyConstraintReason.)
    $this->assertInstanceOf(
      'CultureFeed_Uitpas_Event_TicketSale_Opportunity',
      $events[1]->ticketSales[1]
    );
    $this->assertEquals(
      CultureFeed_Uitpas_Event_TicketSale_Opportunity::TYPE_DEFAULT,
      $events[1]->ticketSales[1]->type
    );
    $this->assertEquals(
      CultureFeed_Uitpas_Event_TicketSale_Opportunity::BUY_CONSTRAINT_INVALID_DATE_CONSTRAINTS,
      $events[1]->ticketSales[1]->buyConstraintReason
    );

    // TicketSale 1 of 3rd event. (Kansentarief)
    $this->assertInstanceOf(
      'CultureFeed_Uitpas_Event_TicketSale_Opportunity',
      $events[2]->ticketSales[0]
    );
    $this->assertEquals(
      CultureFeed_Uitpas_Event_TicketSale_Opportunity::TYPE_DEFAULT,
      $events[2]->ticketSales[0]->type
    );

    $this->assertCount(3, $events[2]->ticketSales[0]->priceClasses);

    $this->assertPriceClass(
      $events[2]->ticketSales[0]->priceClasses[0],
      'Rang 1',
      28.0,
      21.0
    );
    $this->assertPriceClass(
      $events[2]->ticketSales[0]->priceClasses[1],
      'Rang 2',
      21.0,
      14.0
    );
    $this->assertPriceClass(
      $events[2]->ticketSales[0]->priceClasses[2],
      'Rang 3+',
      14.0,
      7.0
    );
  }

  /**
   * @param CultureFeed_Uitpas_PeriodConstraint $object
   * @param string $type
   * @param int $volume
   */
  private function assertPeriodConstraint($object, $type, $volume) {
    $this->assertInstanceOf('CultureFeed_Uitpas_PeriodConstraint', $object);
    $this->assertEquals($type, $object->type);
    $this->assertEquals($volume, $object->volume);
  }

  /**
   * @param CultureFeed_Uitpas_Event_PriceClass $object
   * @param string $name
   * @param float $price
   * @param float|null $tariff
   */
  private function assertPriceClass($object, $name, $price, $tariff = null) {
    $this->assertInstanceOf('CultureFeed_Uitpas_Event_PriceClass', $object);
    $this->assertEquals($name, $object->name);
    $this->assertEquals($price, $object->price);
    $this->assertEquals($tariff, $object->tariff);
  }
}
