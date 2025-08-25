<?php

use PHPUnit\Framework\TestCase;

class CultureFeed_Uitpas_TicketSaleTest extends TestCase {

  /**
   * @test
   */
  public function testCancelTicketSaleById() {
    $oauth_client_stub = $this->createMock('CultureFeed_OAuthClient');
    $ticketId = 1;
    $consumerKey = 'abc';
    $data = array('balieConsumerKey' => $consumerKey);

    $oauth_client_stub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with('uitpas/cultureevent/cancel/' . $ticketId, $data);

    $cf = new CultureFeed($oauth_client_stub);
    $cf->uitpas()->cancelTicketSaleById($ticketId, $consumerKey);
  }
}
