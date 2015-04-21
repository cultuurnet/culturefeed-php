<?php

class CultureFeed_SavedSearches_SavedSearchTest extends PHPUnit_Framework_TestCase {

  public function testSavedSearchToPostData() {
    $saved_search = new CultureFeed_SavedSearches_SavedSearch();

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
    $saved_search->frequency = CultureFeed_SavedSearches_SavedSearch::ASAP;

    $expected = array(
      'id' => 123456,
      'userId' => 123789,
      'name' => 'The name',
      'query' => 'The string',
      'frequency' => CultureFeed_SavedSearches_SavedSearch::ASAP,
    );

    $this->assertEquals($expected, $saved_search->toPostData());
  }
}
