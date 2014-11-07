<?php

/**
 * Class to represent a savedSearch.
 */
class CultureFeed_SavedSearches_SavedSearch {

  /**
   * Possible requency values.
   */
  const ASAP = 'ASAP';
  const DAILY = 'DAILY';
  const WEEKLY = 'WEEKLY';
  const NEVER = 'NEVER';

  /**
   * User id this search is of.
   * @var string
   */
  public $userId;

  /**
   * Name of the search
   * @var string
   */
  public $name;

  /**
   * Query for the search
   * @var string
   */
  public $query;

  /**
   * Frequency to be alerted
   * @var string
   */
  public $frequency;

  /**
   * Convert a CultureFeed_SavedSearches_SavedSearch object to an array that can be used as data in POST requests.
   *
   * @return array
   *   Associative array representing the object. For documentation of the structure, check the CultureFeed API documentation.
   */
  public function toPostData() {
    return get_object_vars($this);
  }

}