<?php

use CultureFeed_SavedSearches_SavedSearch as SavedSearch;

class CultureFeed_SavedSearches_SavedSearchTest extends PHPUnit_Framework_TestCase {

  public function testSavedSearchToPostData() {
    $saved_search = new SavedSearch();

    // Expect an empty array with certain keys and empty values.
    $expected = array(
      'id' => NULL,
      'userId' => NULL,
      'name' => NULL,
      'query' => NULL,
      'frequency' => NULL,
    );

    $this->assertEquals($expected, $saved_search->toPostData());

    // Set data and test if we receive the same data.
    $saved_search->id = 123456;
    $saved_search->userId = 123789;
    $saved_search->name = 'The name';
    $saved_search->query = 'The string';
    $saved_search->frequency = SavedSearch::ASAP;

    $expected = array(
      'id' => 123456,
      'userId' => 123789,
      'name' => 'The name',
      'query' => 'The string',
      'frequency' => SavedSearch::ASAP,
    );

    $this->assertEquals($expected, $saved_search->toPostData());
  }

  public function testConstructorArguments() {
    // Build without arguments.
    $empty_saved_search = new SavedSearch();
    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $empty_saved_search);
    $this->assertEquals($empty_saved_search->id, null);
    $this->assertEquals($empty_saved_search->userId, null);
    $this->assertEquals($empty_saved_search->name, null);
    $this->assertEquals($empty_saved_search->query, null);
    $this->assertEquals($empty_saved_search->frequency, null);

    // Build with arguments.
    $filled_saved_search = new SavedSearch(
      'userId',
      'name',
      'query',
      CultureFeed_SavedSearches_SavedSearch::ASAP,
      9
    );
    $this->assertInstanceOf('CultureFeed_SavedSearches_SavedSearch', $filled_saved_search);
    $this->assertEquals($filled_saved_search->id, 9);
    $this->assertEquals($filled_saved_search->userId, 'userId');
    $this->assertEquals($filled_saved_search->name, 'name');
    $this->assertEquals($filled_saved_search->query, 'query');
    $this->assertEquals($filled_saved_search->frequency, CultureFeed_SavedSearches_SavedSearch::ASAP);

    // Build with an invalid frequency argument.
    $this->setExpectedException('InvalidArgumentException');
    new SavedSearch(
      'userId',
      'name',
      'query',
      'wrong value',
      9
    );
  }
}
