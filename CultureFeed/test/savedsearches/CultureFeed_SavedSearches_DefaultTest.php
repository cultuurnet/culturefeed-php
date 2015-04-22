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

  public function setUp() {
    parent::setUp();

    $this->oauthClientStub = $this->getMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new Culturefeed($this->oauthClientStub);
  }

  public function testGetSavedSearch() {
    $saved_search_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/2')
      ->will($this->returnValue($saved_search_xml));

    $saved_searches_default = new CultureFeed_SavedSearches_Default($this->cultureFeed);
    $result = $saved_searches_default->getSavedSearch(2);

    $expected_xml = new CultureFeed_SimpleXMLElement($saved_search_xml);
    $saved_search_elements = $expected_xml->xpath('savedSearch');
    $saved_search_element = $saved_search_elements[0];

    $id = $saved_search_element->xpath_int('id');
    $frequency = $saved_search_element->xpath_str('frequency');
    $name = $saved_search_element->xpath_str('name');
    $query = $saved_search_element->xpath_str('query');
    $userId = NULL;

    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $result);
    $this->assertEquals($id, $result->id);
    $this->assertEquals($userId, $result->userId);
    $this->assertEquals($name, $result->name);
    $this->assertEquals($query, $result->query);
    $this->assertEquals($frequency, $result->frequency);
  }

  public function testGetSavedSearchWithoutXml() {
    $not_xml = file_get_contents(dirname(__FILE__) . '/data/not_xml.xml');
    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/3')
      ->will($this->returnValue($not_xml));

    $saved_searches_default = new CultureFeed_SavedSearches_Default($this->cultureFeed);

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $saved_searches_default->getSavedSearch(3);
  }

  public function testGetSavedSearchWithIncorrectXml() {
    $incorrect_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_missing_parameter.xml');
    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/4')
      ->will($this->returnValue($incorrect_xml));

    $saved_searches_default = new CultureFeed_SavedSearches_Default($this->cultureFeed);

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $saved_searches_default->getSavedSearch(4);
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

    $saved_searches_default = new CultureFeed_SavedSearches_Default($this->cultureFeed);
    $result = $saved_searches_default->getList(TRUE);

    $expected_xml = new CultureFeed_SimpleXMLElement($saved_search_list_xml);
    $saved_search_elements = $expected_xml->xpath('/response/savedSearches/savedSearch');
    foreach ($saved_search_elements as $saved_search_element) {
      $id = $saved_search_element->xpath_int('id');
      $frequency = $saved_search_element->xpath_str('frequency');
      $name = $saved_search_element->xpath_str('name');
      $query = $saved_search_element->xpath_str('query');
      $userId = NULL;

      $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $result[$id]);
      $this->assertEquals($id, $result[$id]->id);
      $this->assertEquals($userId, $result[$id]->userId);
      $this->assertEquals($name, $result[$id]->name);
      $this->assertEquals($query, $result[$id]->query);
      $this->assertEquals($frequency, $result[$id]->frequency);
    }
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

    $saved_searches_default = new CultureFeed_SavedSearches_Default($this->cultureFeed);

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $saved_searches_default->getList(TRUE);
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

    $saved_searches_default = new CultureFeed_SavedSearches_Default($this->cultureFeed);

    $this->setExpectedException('CultureFeed_ParseException');
    $result = $saved_searches_default->getList(TRUE);
  }

}
