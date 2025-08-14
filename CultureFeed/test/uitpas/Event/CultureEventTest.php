<?php

use PHPUnit\Framework\TestCase;

class CultureFeed_Uitpas_Event_CultureEventTest extends TestCase {

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
    $distributionKey200->id = '200';
    $distributionKey200->name = 'Distribution key 200';

    $distributionKey201 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey201->id = '201';
    $distributionKey201->name = 'Distribution key 201';

    $this->assertIsArray($event->distributionKey);
    $this->assertCount(2, $event->distributionKey);
    $this->assertEquals(
      array(
        $distributionKey200,
        $distributionKey201
      ),
      $event->distributionKey
    );
  }


  public function testToPostDataBasicProperties() {
    $event = new CultureFeed_Uitpas_Event_CultureEvent();

    $event->cdbid = '9ba1b072-40ea-41b6-a66b-ac3fdf646f36';
    $event->organiserId = '5C9C73D3-E82F-E7B3-44161E6E3802E64F';
    $event->locationId = 'afcbe4cf-d873-468b-80f8-7fe583f45e5e';

    $postData = $event->toPostData();

    $this->assertSame(
      array(
        'cdbid' => '9ba1b072-40ea-41b6-a66b-ac3fdf646f36',
        'locationId' => 'afcbe4cf-d873-468b-80f8-7fe583f45e5e',
        'actorId' => '5C9C73D3-E82F-E7B3-44161E6E3802E64F',
      ),
      $postData
    );
  }

  public function testToPostDataWithDistributionKeyAsString() {
    $event = new CultureFeed_Uitpas_Event_CultureEvent();

    $event->distributionKey = '200';

    $postData = $event->toPostData();

    $this->assertArrayHasKey('distributionKey', $postData);
    $this->assertSame('200', $postData['distributionKey']);
  }

  public function testToPostDataWithDistributionKeysAsArrayOfObjects() {
    $event = new CultureFeed_Uitpas_Event_CultureEvent();

    $distributionKey200 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey200->id = '200';
    $distributionKey200->name = 'Distribution key 200';

    $distributionKey201 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey201->id = '201';
    $distributionKey201->name = 'Distribution key 201';

    $event->distributionKey = array(
      $distributionKey200,
      $distributionKey201,
    );

    $postData = $event->toPostData();

    $this->assertArrayHasKey('distributionKey', $postData);
    $this->assertSame(
      array(
        '200',
        '201',
      ),
      $postData['distributionKey']
    );
  }

  public function testPostDataPriceNamesAndValues() {
    $event = new CultureFeed_Uitpas_Event_CultureEvent();

    $event->postPriceNames = array(
      'price 1',
      'price 2'
    );

    $event->postPriceValues = array(
      10.5,
      11.6,
    );

    $postData = $event->toPostData();

    $this->assertEquals(
      array(
        'price.name.1' => 'price 1',
        'price.value.1' => 10.5,
        'price.name.2' => 'price 2',
        'price.value.2' => 11.6
      ),
      array_intersect_key(
        $postData,
        array(
          'price.name.1' => true,
          'price.value.1' => true,
          'price.name.2' => true,
          'price.value.2' => true
        )
      )
    );
  }
}
