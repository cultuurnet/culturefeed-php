<?php
/**
 * @file
 */

class CultureFeed_EntryApiTest extends PHPUnit_Framework_TestCase {

  /**
   * @var CultureFeed_EntryApi
   */
  protected $entry;

  /**
   * @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject
   */
  protected $oauthClient;

  /**
   * @var CultureFeed_Cdb_Item_Event
   */
  protected $event;

  public function setUp() {

    $this->oauthClient = $this->getMock('CultureFeed_OAuthClient');
    $this->entry = new CultureFeed_EntryApi($this->oauthClient);

    $this->event = new CultureFeed_Cdb_Item_Event();
    $this->event->setCdbId('xyz');
  }

  public function testAddTagToEventWithKeywordsAsStrings() {
    $keywords = array(
      'foo',
      'bar',
      'yet another keyword',
    );

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'event/xyz/keywords',
        array(
          'keywords' => 'foo;bar;yet another keyword'
        )
      )
      ->willReturn($this->keywordsCreatedResponse());

    $this->entry->addTagToEvent($this->event, $keywords);
  }

  public function testAddTagToEventWithKeywordsMixedAsObjectsAndStrings() {
    $keywords = array(
      new CultureFeed_Cdb_Data_Keyword('foo', true),
      new CultureFeed_Cdb_Data_Keyword('bar', false),
      'yet another keyword',
    );

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'event/xyz/keywords',
        array(
          'keywords' => 'foo;bar;yet another keyword',
          'visibles' => 'true;false;true',
        )
      )
      ->willReturn($this->keywordsCreatedResponse());

    $this->entry->addTagToEvent($this->event, $keywords);
  }

  public function invalidKeywordProvider() {
    return array(
      array(5),
      array(new stdClass()),
      array(array()),
      array(NULL)
    );
  }

  /**
   * @dataProvider invalidKeywordProvider
   *
   * @param array $keywords
   */
  public function testAddTagToEventWithInvalidKeywords($keyword) {
    $keywords = array($keyword);
    $this->setExpectedException('InvalidArgumentException');
    $this->entry->addTagToEvent($this->event, $keywords);
  }

  private function keywordsCreatedResponse() {
    return file_get_contents(__DIR__ . '/samples/rsp-keywords-created.xml');
  }
}
