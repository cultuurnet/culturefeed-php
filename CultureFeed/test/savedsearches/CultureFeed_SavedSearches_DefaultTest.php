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
   * @var CultureFeed_SavedSearches_SavedSearch
   */
  protected $savedSearchStub;

  /**
   * @var CultureFeed_SavedSearches_Default
   */
  protected $savedSearches;

  public function setUp() {
    parent::setUp();

    $this->oauthClientStub = $this->getMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new Culturefeed($this->oauthClientStub);

    $this->savedSearchStub = new CultureFeed_SavedSearches_SavedSearch(
      '4d177d4e-6810-404c-afe0-e7dba1765f7c',
      'Test+alert+1',
      'q%3Dzwembad',
      CultureFeed_SavedSearches_SavedSearch::ASAP,
      2
    );

    $this->savedSearches = new CultureFeed_SavedSearches_Default($this->cultureFeed);
  }

  public function testSubscribe() {
    $saved_search_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($saved_search_xml));

    $result = $this->savedSearches->subscribe($this->savedSearchStub);

    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $result);
    $this->assertEquals($this->savedSearchStub, $result);
  }

  public function testSubscribeErrorUserNotFound() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_user_not_found.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->setExpectedException('CultureFeed_Exception', 'The user is not found');
    $this->savedSearches->subscribe($this->savedSearchStub);
  }

  public function testSubscribeErrorMissingRequiredFields() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_missing_required_fields.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->setExpectedException('CultureFeed_Exception', '\'name\' is a required parameter');
    $this->savedSearches->subscribe($this->savedSearchStub);
  }

  public function testSubscribeErrorInvalidParameters() {
    $subscribe_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_invalid_parameters.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/subscribe',
        $this->savedSearchStub->toPostData()
      )
      ->will($this->returnValue($subscribe_xml));

    $this->setExpectedException('CultureFeed_Exception', '\'name\' is a required parameter');
    $this->savedSearches->subscribe($this->savedSearchStub);
  }

  public function testUnsubscribe() {
    $unsubscribe_xml = file_get_contents(dirname(__FILE__) . '/data/unsubscribe.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/' . $this->savedSearchStub->id . '/unsubscribe',
        array('userId' => $this->savedSearchStub->userId)
      )
      ->will($this->returnValue($unsubscribe_xml));

    $this->savedSearches->unsubscribe($this->savedSearchStub->id, $this->savedSearchStub->userId);
  }

  public function testUnsubscribeErrorUserNotFound() {
    $unsubscribe_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_user_not_found.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/' . $this->savedSearchStub->id . '/unsubscribe',
        array('userId' => $this->savedSearchStub->userId)
      )
      ->will($this->returnValue($unsubscribe_xml));

    $this->setExpectedException('CultureFeed_Exception', 'The user is not found');
    $this->savedSearches->unsubscribe($this->savedSearchStub->id, $this->savedSearchStub->userId);
  }

  public function testUnsubscribeErrorInvalidParameters() {
    $unsubscribe_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_invalid_parameters.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/' . $this->savedSearchStub->id . '/unsubscribe',
        array('userId' => $this->savedSearchStub->userId)
      )
      ->will($this->returnValue($unsubscribe_xml));

    $this->setExpectedException('CultureFeed_Exception', '\'name\' is a required parameter');
    $this->savedSearches->unsubscribe($this->savedSearchStub->id, $this->savedSearchStub->userId);
  }

  public function testChangeFrequency() {
    $saved_search_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/' . $this->savedSearchStub->id . '/frequency',
        array('frequency' => $this->savedSearchStub->frequency)
      )
      ->will($this->returnValue($saved_search_xml));

    $result = $this->savedSearches->changeFrequency($this->savedSearchStub->id, $this->savedSearchStub->frequency);

    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $result);
    $this->assertEquals($this->savedSearchStub, $result);
  }

  public function testChangeFrequencyErrorUserNotFound() {
    $not_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_user_not_found.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/' . $this->savedSearchStub->id . '/frequency',
        array('frequency' => $this->savedSearchStub->frequency)
      )
      ->will($this->returnValue($not_xml));

    $this->setExpectedException('CultureFeed_Exception', 'The user is not found');
    $this->savedSearches->changeFrequency($this->savedSearchStub->id, $this->savedSearchStub->frequency);
  }

  public function testChangeFrequencyErrorInvalidParameters() {
    $incorrect_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_error_invalid_parameters.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'savedSearch/' . $this->savedSearchStub->id . '/frequency',
        array('frequency' => $this->savedSearchStub->frequency)
      )
      ->will($this->returnValue($incorrect_xml));

    $this->setExpectedException('CultureFeed_Exception', '\'name\' is a required parameter');
    $this->savedSearches->changeFrequency($this->savedSearchStub->id, $this->savedSearchStub->frequency);
  }

  public function testGetSavedSearch() {
    $saved_search_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/2')
      ->will($this->returnValue($saved_search_xml));

    $result = $this->savedSearches->getSavedSearch(2);

    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $result);
    $this->assertEquals($this->savedSearchStub, $result);
  }

  public function testGetSavedSearchWithoutXml() {
    $not_xml = file_get_contents(dirname(__FILE__) . '/data/not_xml.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/3')
      ->will($this->returnValue($not_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $this->savedSearches->getSavedSearch(3);
  }

  public function testGetSavedSearchWithIncorrectXml() {
    $incorrect_xml = file_get_contents(dirname(__FILE__) . '/data/savedsearch_missing_parameter.xml');

    $this->oauthClientStub->expects($this->once())
      ->method('authenticatedGetAsXml')
      ->with('savedSearch/4')
      ->will($this->returnValue($incorrect_xml));

    $this->setExpectedException('CultureFeed_ParseException');
    $this->savedSearches->getSavedSearch(4);
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

    $savedSearch2 = new CultureFeed_SavedSearches_SavedSearch(
      '4d177d4e-6810-404c-afe0-e7dba1765f7c',
      'Test+alert+1',
      'q%3Dzwembad',
      CultureFeed_SavedSearches_SavedSearch::ASAP,
      2
    );
    $savedSearch3 = new CultureFeed_SavedSearches_SavedSearch(
      '4d177d4e-6810-404c-afe0-e7dba1765f7c',
      'UitAlert+2',
      'q%3Dtheater',
      CultureFeed_SavedSearches_SavedSearch::WEEKLY,
      3
    );

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
