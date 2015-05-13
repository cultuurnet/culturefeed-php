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

    public function testAddWebLinkToEvent() {

        $lang = 'nl';
        $link = 'http://tools.uitdatabank.be';
        $link_type = CultureFeed_Cdb_Data_File::MEDIA_TYPE_WEBSITE;

        $this->oauthClient->expects($this->once())
            ->method('authenticatedPostAsXml')
            ->with(
                'event/xyz/links',
                array(
                    'lang' => $lang,
                    'link' => $link,
                    'linktype' => $link_type,
                    'title' => '',
                    'copyright' => '',
                    'plaintext' => '',
                    'subbrand' => '',
                    'description' => '',
                )
            )
            ->willReturn($this->linkCreatedResponse()
        );

        $this->entry->addLinkToEvent($this->event, $link, $link_type, $lang);

    }

    public function testAddCollaborationLinkToEvent() {

        $copyright = 'copyright';
        $description = 'description';
        $lang = 'nl';
        $link_type = CultureFeed_Cdb_Data_File::MEDIA_TYPE_ROADMAP;
        $plain_text = 'plaint text';
        $sub_brand = 'consumer key';
        $title = 'title';

        $this->oauthClient->expects($this->once())
            ->method('authenticatedPostAsXml')
            ->with(
                'event/xyz/links',
                array(
                    'lang' => $lang,
                    'link' => '',
                    'linktype' => $link_type,
                    'title' => $title,
                    'copyright' => $copyright,
                    'plaintext' => $plain_text,
                    'subbrand' => $sub_brand,
                    'description' => $description,
                )
            )
            ->willReturn($this->linkCreatedResponse()
        );

        $this->entry->addLinkToEvent($this->event, '', $link_type, $lang, $title, $copyright, $plain_text, $sub_brand, $description);

    }

    public function testAddLinkToEventWithIncompleteLink() {

       $this->setExpectedException('InvalidArgumentException');
       $this->entry->addLinkToEvent($this->event, '', CultureFeed_Cdb_Data_File::MEDIA_TYPE_WEBSITE, 'nl');

    }

    private function linkCreatedResponse() {
        return file_get_contents(__DIR__ . '/samples/rsp-link-created.xml');
    }

}
