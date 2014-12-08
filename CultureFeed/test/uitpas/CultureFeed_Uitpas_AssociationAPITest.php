<?php
/**
 * @file
 */

class CultureFeed_Uitpas_AssociationAPITest extends PHPUnit_Framework_TestCase {

  public function testGetAssociations() {
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $balie_consumer_key = 'e52efb7f-2eab-47a5-9cf3-9e7413ffd942';

    $xml = file_get_contents(dirname(__FILE__) . '/data/associations/list.xml');

    $oauth_client_stub
      ->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('uitpas/association/list', array(
          'balieConsumerKey' => $balie_consumer_key,
        ))
      ->will($this->returnValue($xml));

    $cf = new CultureFeed($oauth_client_stub);

    $result = $cf->uitpas()->getAssociations($balie_consumer_key);

    $this->assertInstanceOf('CultureFeed_ResultSet', $result);
    $this->assertEquals(3, $result->total);

    $this->assertInternalType('array', $result->objects);
    $this->assertCount(3, $result->objects);
    $this->assertContainsOnly('CultureFeed_Uitpas_Association', $result->objects);

    /* @var CultureFeed_Uitpas_Association $association */
    $association = reset($result->objects);

    $this->assertEquals(1, $association->id);
    $this->assertEquals('CJP', $association->name);
    $this->assertInstanceOf('CultureFeed_Uitpas_CardSystem', $association->cardSystem);
    $this->assertEquals(6, $association->cardSystem->id);
    $this->assertEquals('Testsysteem Paspartoe', $association->cardSystem->name);
    $this->assertSame(TRUE, $association->permissionRead);
    $this->assertSame(TRUE, $association->permissionRegister);
    $this->assertSame(CultureFeed_Uitpas_EndDateCalculation::FREE, $association->enddateCalculation);
    $this->assertSame(1451602799, $association->enddateCalculationFreeDate);


    $association = next($result->objects);

    $this->assertEquals(2, $association->id);
    $this->assertEquals('Okra', $association->name);
    $this->assertInstanceOf('CultureFeed_Uitpas_CardSystem', $association->cardSystem);
    $this->assertEquals(1, $association->cardSystem->id);
    $this->assertEquals('HELA', $association->cardSystem->name);
    $this->assertSame(FALSE, $association->permissionRead);
    $this->assertSame(FALSE, $association->permissionRegister);
    $this->assertSame(CultureFeed_Uitpas_EndDateCalculation::BASED_ON_REGISTRATION_DATE, $association->enddateCalculation);
    $this->assertSame(1, $association->enddateCalculationValidityTime);

    $association = next($result->objects);

    $this->assertEquals(3, $association->id);
    $this->assertEquals('Foo', $association->name);
    $this->assertInstanceOf('CultureFeed_Uitpas_CardSystem', $association->cardSystem);
    $this->assertEquals(1, $association->cardSystem->id);
    $this->assertEquals('HELA', $association->cardSystem->name);
    $this->assertSame(TRUE, $association->permissionRead);
    $this->assertSame(TRUE, $association->permissionRegister);
    $this->assertSame(CultureFeed_Uitpas_EndDateCalculation::BASED_ON_DATE_OF_BIRTH, $association->enddateCalculation);
    $this->assertSame(22, $association->enddateCalculationValidityTime);
  }
} 
