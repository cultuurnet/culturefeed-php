<?php

class CultureFeed_Uitpas_EventDetailTest extends PHPUnit_Framework_TestCase {

  /**
   * Test retrieval of the details of an event.
   */
  public function testGetEvent() {
    $response = <<<XML
<response>
    <cdbid>e642dc9e-4682-4846-ac61-97a9a0cd38a2</cdbid>
    <checkinConstraint>
        <periodType>WEEK</periodType>
        <periodVolume>1</periodVolume>
    </checkinConstraint>
    <gracePeriodMonths>0</gracePeriodMonths>
    <numberOfPoints>1</numberOfPoints>
    <ticketSalesConstraint>
        <periodConstraint>
            <periodType>ABSOLUTE</periodType>
            <periodVolume>1</periodVolume>
        </periodConstraint>
        <volume>0</volume>
    </ticketSalesConstraint>
    <distributionKeys>
        <distributionKey>
            <id>5263</id>
            <name>Foo</name>
        </distributionKey>
        <distributionKey>
            <id>5264</id>
            <name>Bar</name>
        </distributionKey>
    </distributionKeys>
</response>
XML;

    /* @var $oauth_client_stub PHPUnit_Framework_MockObject_MockObject|CultureFeed_OAuthClient */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');
    $oauth_client_stub
      ->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        $this->equalTo('uitpas/cultureevent/e642dc9e-4682-4846-ac61-97a9a0cd38a2')
      )
      ->will($this->returnValue($response));

    $cf = new CultureFeed($oauth_client_stub);

    $event = $cf->uitpas()->getEvent('e642dc9e-4682-4846-ac61-97a9a0cd38a2');

    $this->assertInstanceOf('\CultureFeed_Uitpas_Event_CultureEvent', $event);

    $this->assertSame('e642dc9e-4682-4846-ac61-97a9a0cd38a2', $event->cdbid);
    $this->assertSame(1, $event->numberOfPoints);

    $firstDistributionKey = new CultureFeed_Uitpas_DistributionKey();
    $firstDistributionKey->id = '5263';
    $firstDistributionKey->name = 'Foo';

    $secondDistributionKey = new CultureFeed_Uitpas_DistributionKey();
    $secondDistributionKey->id = '5264';
    $secondDistributionKey->name = 'Bar';

    $expectedDistributionKeys = [
      $firstDistributionKey,
      $secondDistributionKey,
    ];

    $this->assertEquals($expectedDistributionKeys, $event->distributionKey);
  }
}
