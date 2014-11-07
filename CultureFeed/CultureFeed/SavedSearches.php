<?php

/**
 * @class
 * Interface for savedSearches implementations.
 */
interface CultureFeed_SavedSearches {

  /**
   * Subscribe a new saved search.
   * @param CultureFeed_SavedSearches_SavedSearch $savedSearch
   *   SavedSearch to subscribe.
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch);

}
