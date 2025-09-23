<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @file
 * PHPUnit Testing the registering of an event.
 */

class CultureFeed_Uitpas_EventUpdateTest extends TestCase {

  /**
   * Test the update of an event.
   */
  public function testUpdateEvent(): void {
    $event = new CultureFeed_Uitpas_Event_CultureEvent();
    $event->cdbid = 'da4cf0be-b28b-4b1d-b66f-50adc5638594';
    $event->organiserId = 'b101b61b-1d91-4216-908e-2c0ac16bc490';
    $event->locationId = 'abd76139-5b0d-42b1-ba5b-a40172e27fba';

    $distributionKey200 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey200->id = 200;
    $distributionKey200->name = 'Distribution key 200';

    $distributionKey201 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey201->id = 201;
    $distributionKey201->name = 'Distribution key 201';

    $response = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<response>
  <code>ACTION_SUCCEEDED</code>
  <message>De event gegevens werden aangepast.</message>
</response>
XML;

    /* @var $oauth_client_stub CultureFeed_OAuthClient&MockObject */
    $oauth_client_stub = $this->createMock('CultureFeed_OAuthClient');
    $oauth_client_stub
      ->expects($this->once())
      ->method('consumerPostAsXml')
      ->with(
        $this->equalTo('uitpas/cultureevent/update'),
        $this->equalTo(
          array(
            'cdbid' => $event->cdbid,
            'locationId' => $event->locationId,
            'actorId' => $event->organiserId,
            'distributionKey' => array(
              $distributionKey200->id,
              $distributionKey201->id,
            )
          )
        )
      )
      ->will($this->returnValue($response));

    $event->distributionKey = array(
      $distributionKey200,
      $distributionKey201,
    );

    $cf = new CultureFeed($oauth_client_stub);

    $response = $cf->uitpas()->updateEvent($event);

    $this->assertInstanceOf('\CultureFeed_Uitpas_Response', $response);

    $this->assertEquals('ACTION_SUCCEEDED', $response->code);
    $this->assertEquals('De event gegevens werden aangepast.', $response->message);
  }

}
