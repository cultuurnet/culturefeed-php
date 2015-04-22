<?php

class CultureFeed_SavedSearches_DefaultTest extends PHPUnit_Framework_TestCase {
  /**
   * @var Culturefeed
   */
  protected $cultureFeed;

  /**
   * @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject
   */
  protected $oauthClientStub;

  /**
   * @var CultureFeed_SavedSearches_Default
   */
  protected $savedSearches;

  public function setUp() {
    parent::setUp();

    $this->oauthClientStub = $this->getMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new Culturefeed($this->oauthClientStub);

    $this->savedSearches = new CultureFeed_SavedSearches_Default($this->cultureFeed);
  }

  public function testGetSavedSearch() {
    $saved_search_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/2')
      ->will($this->returnValue($saved_search_xml));

    $result = $this->savedSearches->getSavedSearch(2);

    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $result);
    $this->assertEquals('2', $result->id);
    // @todo The user id currently doesn't get parsed.
    // $this->assertEquals('4d177d4e-6810-404c-afe0-e7dba1765f7c', $result->userId);
    $this->assertEquals('Test+alert+1', $result->name);
    $this->assertEquals('q%3Dzwembad', $result->query);
    $this->assertEquals(CultureFeed_SavedSearches_SavedSearch::ASAP, $result->frequency);
  }

  public function testGetSavedSearchWithoutXml() {
    $not_xml = file_get_contents(dirname(__FILE__) . '/data/not_xml.xml');
    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/3')
      ->will($this->returnValue($not_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $this->savedSearches->getSavedSearch(3);
  }

  public function testGetSavedSearchWithIncorrectXml() {
    $incorrect_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_missing_parameter.xml');
    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/4')
      ->will($this->returnValue($incorrect_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $this->savedSearches->getSavedSearch(4);
  }

  public function testGetList() {
    $saved_search_list_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearchlist.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with(
        $this->equalTo('savedSearch/list'),
        $this->equalTo(array(
          'all' => 'true',
        ))
        )
      ->will($this->returnValue($saved_search_list_xml));

    $result = $this->savedSearches->getList(TRUE);

    $savedSearch2 = new CultureFeed_SavedSearches_SavedSearch();
    $savedSearch2->id = 2;
    $savedSearch2->name = 'Test+alert+1';
    $savedSearch2->query = 'q%3Dzwembad';
    $savedSearch2->frequency = $savedSearch2::ASAP;

    $savedSearch3 = new CultureFeed_SavedSearches_SavedSearch();
    $savedSearch3->id = 3;
    $savedSearch3->name = 'UitAlert+2';
    $savedSearch3->query = 'q%3Dtheater';
    $savedSearch3->frequency = $savedSearch3::WEEKLY;

    $this->assertEquals(
      array(
        $savedSearch2->id => $savedSearch2,
        $savedSearch3->id => $savedSearch3,
      ),
      $result
    );
  }

  public function testGetListWithoutXml() {
    $not_xml = file_get_contents(dirname(__FILE__) . '/data/not_xml.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with(
        $this->equalTo('savedSearch/list'),
        $this->equalTo(array(
          'all' => 'true',
        ))
      )
      ->will($this->returnValue($not_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $this->savedSearches->getList(TRUE);
  }

  public function testGetListWithIncorrectXml() {
    $saved_search_list_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearchlist_missing_parameter.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with(
        $this->equalTo('savedSearch/list'),
        $this->equalTo(array(
          'all' => 'true',
        ))
      )
      ->will($this->returnValue($saved_search_list_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $this->savedSearches->getList(TRUE);
  }

}
