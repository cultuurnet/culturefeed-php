<?php
/**
 * @file
 */

class CultureFeed_Uitpas_GroupPassAPITest extends PHPUnit_Framework_TestCase {

  public function testGetGroupPass() {
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $xml = file_get_contents(dirname(__FILE__) . '/data/grouppass/groupPass.xml');

    $id = '1000000265008';

    $oauth_client_stub
      ->expects($this->once())
      ->method('consumerGetAsXml')
      ->with('uitpas/grouppass/' . $id)
      ->will($this->returnValue($xml));

    $cf = new CultureFeed($oauth_client_stub);

    $pass = $cf->uitpas()->getGroupPass($id);

    $this->assertInstanceOf('CultureFeed_Uitpas_GroupPass', $pass);
    $this->assertEquals('Davidsfonds test Anna', $pass->name);
    $this->assertEquals(1451516400, $pass->endDate);
    $this->assertEquals(40, $pass->ticketsPerYear);
    $this->assertEquals('83cd39ff-c08b-4afd-8491-a27e8a1c085c', $pass->uuid);
    $this->assertEquals(0, $pass->availableTickets);
    $this->assertEquals(false, $pass->kansenStatuut);
    $this->assertInternalType('array', $pass->ticketSaleCoupons);

    $coupon = reset($pass->ticketSaleCoupons);

    $this->assertInstanceOf('CultureFeed_Uitpas_SaleCoupon', $coupon);
    $this->assertInternalType('array', $coupon->buyConstraint);
    $this->assertEquals('ABSOLUTE', $coupon->buyConstraint['periodType']);
    $this->assertEquals(50, $coupon->buyConstraint['periodVolume']);
    $this->assertEquals('bon voor groep id AB', $coupon->description);
    $this->assertInternalType('array', $coupon->exchangeConstraint);
    $this->assertEquals('ABSOLUTE', $coupon->exchangeConstraint['periodType']);
    $this->assertEquals(50, $coupon->exchangeConstraint['periodVolume']);
    $this->assertEquals(false, $coupon->expired);
    $this->assertEquals(14, $coupon->id);
    $this->assertEquals('AB groepsbon', $coupon->name);
    $this->assertInternalType('array', $coupon->remainingTotal);
    $this->assertEquals('ABSOLUTE', $coupon->remainingTotal['periodType']);
    $this->assertEquals(50, $coupon->remainingTotal['periodVolume']);
    $this->assertEquals(1475359200, $coupon->validFrom);
    $this->assertEquals(1477087199, $coupon->validTo);

  }
} 
