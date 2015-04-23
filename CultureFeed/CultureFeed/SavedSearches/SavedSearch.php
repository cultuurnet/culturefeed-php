<?php

/**
 * Class to represent a savedSearch.
 */
class CultureFeed_SavedSearches_SavedSearch {

  /**
   * Possible frequency values.
   */
  const ASAP = 'ASAP';
  const DAILY = 'DAILY';
  const WEEKLY = 'WEEKLY';
  const NEVER = 'NEVER';

  /**
   * ID of the saved search.
   *
   * @var int
   */
  public $id;

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
   * Constructor for a new CultureFeed_SavedSearches_SavedSearch instance.
   *
   * @param string $userId
   *   The id of the user who saved the search.
   * @param string $name
   *   The name of the saved search.
   * @param string $query
   *   The query of the saved search.
   * @param string $frequency
   *   The frequency of the alerts of the saved search.
   * @param int $id
   *   The id of the saved search.
   */
  public function __construct($userId = null, $name = null, $query = null, $frequency = null, $id = null) {
    $this->id = $id;
    $this->userId = $userId;
    $this->name = $name;
    $this->query = $query;
    if ($frequency !== null) {
      $this->setFrequency($frequency);
    }
  }

  /**
   * Sets the saved search frequency variable.
   *
   * @param string $frequency
   *   The frequency value to set.
   * @throws InvalidArgumentException
   *   When an invalid frequency value is given.
   */
  public function setFrequency($frequency) {
    $allowed_frequency_values = array(
      $this::ASAP,
      $this::DAILY,
      $this::WEEKLY,
      $this::NEVER
    );

    if (!in_array($frequency, $allowed_frequency_values)) {
      throw new InvalidArgumentException('Invalid value for frequency: ' . $frequency);
    }

    $this->frequency = $frequency;
  }

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
