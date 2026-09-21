<?php

use PHPUnit\Framework\TestCase;

class CultureFeed_DefaultOAuthClientTest extends TestCase {

  public function testRequestReturnsTheResponseBody(): void {
    $body = '<?xml version="1.0" encoding="UTF-8"?><consumer><name>Example Corp.</name></consumer>';

    $http_client_stub = $this->createMock('CultureFeed_HttpClient');
    $http_client_stub->expects($this->once())
             ->method('request')
             ->willReturn(new CultureFeed_HttpResponse(200, $body));

    $client = new CultureFeed_DefaultOAuthClient('consumer-key', 'consumer-secret');
    $client->setEndpoint('http://example.com/');
    $client->setHttpClient($http_client_stub);

    $this->assertSame($body, $client->consumerGetAsXml('serviceconsumer/apikey/api-key'));
  }

  public function testRequestThrowsAnHttpExceptionOnANonOkStatus(): void {
    $http_client_stub = $this->createMock('CultureFeed_HttpClient');
    $http_client_stub->expects($this->once())
             ->method('request')
             ->willReturn(new CultureFeed_HttpResponse(404, 'Not found'));

    $client = new CultureFeed_DefaultOAuthClient('consumer-key', 'consumer-secret');
    $client->setEndpoint('http://example.com/');
    $client->setHttpClient($http_client_stub);

    $this->expectException('CultureFeed_HttpException');

    $client->consumerGetAsXml('serviceconsumer/apikey/api-key');
  }

}
