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
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch);

  /**
   * Get a list of all savedSearches for current user.
   *
   * @param bool $allConsumers
   *   Give a list of savedsearches in all consumers, or only on current consumer.
   * @return array
   *   List of savedSearches.
   */
  public function getList($allConsumers = FALSE);

}
