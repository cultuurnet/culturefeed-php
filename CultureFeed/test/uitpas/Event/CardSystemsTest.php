<?php

class CultureFeed_Uitpas_Event_CardSystemsTest extends PHPUnit_Framework_TestCase {
  const EVENTCDBID = "47B6FA21-ACB1-EA8F-2C231182C7DD0A19";

  public function testGetCardSystemsForEvent() {
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/../data/cultureevent/getCardSystems.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $data = $cf->uitpas()->getCardSystemsForEvent(self::EVENTCDBID);

    $this->assertInstanceOf('CultureFeed_ResultSet', $data);

    $this->assertContainsOnly('CultureFeed_Uitpas_CardSystem', $data->objects);
    $this->assertCount(1, $data->objects);
    $this->assertEquals(1, $data->total);

    /* @var \CultureFeed_Uitpas_CardSystem $cardSystem */
    $cardSystem = reset($data->objects);

    $this->assertEquals(1, $cardSystem->id);
    $this->assertEquals("UiTPAS Dender", $cardSystem->name);

    $this->assertContainsOnly('CultureFeed_Uitpas_DistributionKey', $cardSystem->distributionKeys);
    $this->assertCount(1, $cardSystem->distributionKeys);

    $this->assertEquals(27, $cardSystem->distributionKeys[0]->id);
    $this->assertEquals('Speelplein HA', $cardSystem->distributionKeys[0]->name);
  }
}
