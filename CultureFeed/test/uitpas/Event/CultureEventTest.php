<?php

class CultureFeed_Uitpas_Event_CultureEventTest extends PHPUnit_Framework_TestCase {

  public function testCreateFromXml() {
    $event = CultureFeed_Uitpas_Event_CultureEvent::createFromXML(
      new CultureFeed_SimpleXMLElement(
        file_get_contents(__DIR__ . '/../data/events/event.xml')
      )
    );

    $this->assertInstanceOf('CultureFeed_Uitpas_Event_CultureEvent', $event);
    $this->assertSame('9ba1b072-40ea-41b6-a66b-ac3fdf646f36', $event->cdbid);
    $this->assertSame('5C9C73D3-E82F-E7B3-44161E6E3802E64F', $event->locationId);
    $this->assertSame('5c9c73d3-e82f-e7b3-44161e6e3802e64f', $event->organiserId);

    $distributionKey200 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey200->id = 200;
    $distributionKey200->name = 'Distribution key 200';

    $distributionKey201 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey201->id = 201;
    $distributionKey201->name = 'Distribution key 201';

    $this->assertInternalType('array', $event->distributionKey);
    $this->assertCount(2, $event->distributionKey);
    $this->assertEquals(
      array(
        $distributionKey200,
        $distributionKey201
      ),
      $event->distributionKey
    );
  }

}
