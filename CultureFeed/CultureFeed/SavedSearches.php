<?php

/**
 * @class
 * Interface for savedSearches implementations.
 */
interface CultureFeed_SavedSearches {

  /**
   * Subscribe a new saved search.
   *
   * @param CultureFeed_SavedSearches_SavedSearch $savedSearch
   *   SavedSearch to subscribe.
   * @param bool $use_auth
   *   Using a consumer request for this method is only available for a few consumers who have the ‘Use Light UiTID permission.
   * @return CultureFeed_SavedSearches_SavedSearch
   *   The saved search object.
   * @throws \CultureFeed_ParseException
   *   If the result could not be parsed.
   * @throws \CultureFeed_Exception
   *   If the XML element contains an error code.
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch, $use_auth = TRUE);

  /**
   * Unsubscribe a user from a saved search.
   *
   * @param int $savedSearchId
   *   Saved search id to unscribe from.
   * @param string $userId
   *   UserId to unsubscribe.
   * @param bool $use_auth
   *   Using a consumer request for this method is only available for a few consumers who have the ‘Use Light UiTID permission.
   * @return string
   *   A success message.
   * @throws \CultureFeed_ParseException
   *   If the result could not be parsed.
   * @throws \CultureFeed_Exception
   *   If the XML element contains an error code.
   */
  public function unsubscribe($savedSearchId, $userId, $use_auth = TRUE);

  /**
   * Change the frequency of a saved search.
   *
   * @param int $savedSearchId
   *   Saved search id to change.
   * @param string $frequency
   *   New frequency to set.
   * @return CultureFeed_SavedSearches_SavedSearch
   *   The updated saved search object.
   * @throws \CultureFeed_ParseException
   *   If the result could not be parsed.
   * @throws \CultureFeed_Exception
   *   If the XML element contains an error code.
   */
  public function changeFrequency($savedSearchId, $frequency);

  /**
   * Get a list of all saved searches for current user.
   *
   * @param bool $allConsumers
   *   Give a list of saved searches in all consumers, or only on current consumer.
   * @return CultureFeed_SavedSearches_SavedSearch[]
   *   List of saved searches.
   * @throws \CultureFeed_ParseException
   *   If the result could not be parsed.
   * @throws \CultureFeed_Exception
   *   If the XML element contains an error code.
   */
  public function getList($allConsumers = FALSE);

  /**
   * Load a saved search by id.
   *
   * @param int $savedSearchId
   *   ID of the saved search.
   * @return CultureFeed_SavedSearches_SavedSearch
   *   The requested saved search object.
   * @throws \CultureFeed_ParseException
   *   If the result could not be parsed.
   * @throws \CultureFeed_Exception
   *   If the XML element contains an error code.
   */
  public function getSavedSearch($savedSearchId);

}
