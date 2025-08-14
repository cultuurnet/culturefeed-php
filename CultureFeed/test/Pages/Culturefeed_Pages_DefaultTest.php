<?php

use PHPUnit\Framework\TestCase;

/**
 * @file
 * Testing methods for the Pages_Default class.
 */

class CultureFeed_Pages_DefaultTest extends TestCase {

  /**
   * @var Culturefeed
   */
  protected $cultureFeed;

  /**
   * @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject
   */
  protected $oauthClient;

  /**
   * @var CultureFeed_Pages_Default
   */
  protected $pages;

  public function setUp() {
    parent::setUp();

    $this->oauthClient = $this->createMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new CultureFeed($this->oauthClient);
    $this->pages = new CultureFeed_Pages_Default($this->cultureFeed);
  }

  /**
   * Test the modifying of a page
   */
  public function testModifyPage() {

    $modified_xml = file_get_contents(dirname(__FILE__) . '/data/page_modified.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPost')
      ->with(
        'page/f412b8a3-8b12-44bc-9a0a-c74ec0e88e98',
        array('name' => 'test')
      )
    ->will($this->returnValue($modified_xml));

    $uid = $this->pages->updatePage('f412b8a3-8b12-44bc-9a0a-c74ec0e88e98', array('name' => 'test'));
//    $this->assertEquals($uid, 'f412b8a3-8b12-44bc-9a0a-c74ec0e88e98');

  }

}
