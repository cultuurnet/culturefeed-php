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

    $this->savedSearchStub = new CultureFeed_SavedSearches_SavedSearch();
    $this->savedSearchStub->id = 4;
    $this->savedSearchStub->userId = '4d177d4e-6810-404c-afe0-e7dba1765f7c';
    $this->savedSearchStub->name = 'Alles';
    $this->savedSearchStub->query = 'q=*&past=true&fq=type:event&group=true';
    $this->savedSearchStub->frequency = CultureFeed_SavedSearches_SavedSearch::ASAP;

    $this->savedSearches = new CultureFeed_SavedSearches_Default($this->cultureFeed);
  }

  public function testSubscribe() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->savedSearches->subscribe($this->savedSearchStub);
  }

  public function testSubscribeErrorUserNotFound() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_error_user_not_found.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $this->savedSearches->subscribe($this->savedSearchStub);
  }

  public function testSubscribeErrorMissingRequiredFields() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_error_missing_required_fields.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $this->savedSearches->subscribe($this->savedSearchStub);
  }

  public function testSubscribeErrorInvalidParameters() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_error_invalid_parameters.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $this->savedSearches->subscribe($this->savedSearchStub);
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
