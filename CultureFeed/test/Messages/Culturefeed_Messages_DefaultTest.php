<?php

/**
 * @file
 * Testing methods for the messages default class.
 */

class CultureFeed_Messages_DefaultTest extends PHPUnit_Framework_TestCase {

  /**
   * @var Culturefeed
   */
  protected $cultureFeed;

  /**
   * @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject
   */
  protected $oauthClient;

  /**
   * @var CultureFeed_Messages_Default
   */
  protected $messages;

  public function setUp() {
    parent::setUp();

    $this->oauthClient = $this->getMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new CultureFeed($this->oauthClient);
    $this->messages = new CultureFeed_Messages_Default($this->cultureFeed);
    $this->message = new CultureFeed_Messages_Message();
    $this->message->id = '32233';
  }

  /**
   * Test the requesting of a message
   */
  public function testGetMessage() {

    $message_xml = file_get_contents(dirname(__FILE__) . '/data/message.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with(
        'message/1'
      )
    ->will($this->returnValue($message_xml));

    $message = $this->messages->getMessage(1);

    $this->assertInstanceOf('CultureFeed_Messages_Message', $message);
    $this->assertEquals($this->message->id, $message->id);

  }

}