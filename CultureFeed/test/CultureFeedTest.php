<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CultureFeed_CultureFeedTest extends PHPUnit_Framework_TestCase {

  /**
   * @var Culturefeed
   */
  protected $cultureFeed;

  /**
   * @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject
   */
  protected $oauthClient;

  public function setUp() {
    parent::setUp();

    $this->oauthClient = $this->getMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new CultureFeed($this->oauthClient);
  }

  public function testGetUserLightId() {

    $success_xml = file_get_contents(dirname(__FILE__) . '/data/user_light_success.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/light',
        array('email' => 'test@test.be', 'homeZip' => '')
      )
      ->will($this->returnValue($success_xml));

    $uid = $this->cultureFeed->getUserLightId('test@test.be', '');
    $this->assertEquals('400118cc-d251-4eed-a36b-8fc5c2689f12', $uid);

  }

  /**
   * @expectedException Culturefeed_ParseException
   */
  public function testGetUserLightIdEmptyXmlParseException() {

    $without_uid_xml = file_get_contents(dirname(__FILE__) . '/data/user_light_without_uid.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/light',
        array('email' => 'test@test.be', 'homeZip' => '')
      )
      ->will($this->returnValue($without_uid_xml));

    $this->cultureFeed->getUserLightId('test@test.be', '');

  }

  /**
   * @expectedException Culturefeed_ParseException
   */
  public function testGetUserLightInvalidXmlParseException() {

    $invalid_xml = file_get_contents(dirname(__FILE__) . '/data/user_light_invalid_xml.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/light',
        array('email' => 'test@test.be', 'homeZip' => '')
      )
      ->will($this->returnValue($invalid_xml));

    $this->cultureFeed->getUserLightId('test@test.be', '');

  }

  public function testSubscribeToMailingAuthenticated() {

    $subscribe_to_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_to_mailing_xml.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'mailing/v2/3/subscribe',
        array('userId' => 1)
      )
    ->will($this->returnValue($subscribe_to_mailing_xml));

    $response = $this->cultureFeed->subscribeToMailing(1, 3);

  }

  public function testSubscribeToMailingConsumer() {

    $subscribe_to_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_to_mailing_xml.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerPostAsXml')
      ->with(
        'mailing/v2/3/subscribe',
        array('userId' => 1)
      )
    ->will($this->returnValue($subscribe_to_mailing_xml));

    $response = $this->cultureFeed->subscribeToMailing(1, 3, FALSE);
  }

  public function testUnSubscribeFromMailingAuthenticated() {

    $unsubscribe_from_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/unsubscribe_from_mailing_xml.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'mailing/v2/3/unsubscribe',
        array('userId' => 1)
      )
      ->will($this->returnValue($unsubscribe_from_mailing_xml));

    $response = $this->cultureFeed->unSubscribeFromMailing(1, 3);
  }

  public function testUnSubscribeFromMailingConsumer() {

    $unsubscribe_from_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/unsubscribe_from_mailing_xml.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerPostAsXml')
      ->with(
        'mailing/v2/3/unsubscribe',
        array('userId' => 1)
      )
      ->will($this->returnValue($unsubscribe_from_mailing_xml));

    $response = $this->cultureFeed->unSubscribeFromMailing(1, 3, FALSE);

  }

}