<?php

/**
 * @class
 * Contains all methods for saved searches.
 */
class CultureFeed_SavedSearches_Default implements CultureFeed_SavedSearches {

  /**
   * CultureFeed object to make CultureFeed core requests.
   * @var ICultureFeed
   */
  protected $culturefeed;

  /**
   * OAuth request object to do the request.
   *
   * @var CultureFeed_OAuthClient
   */
  protected $oauth_client;

  public function __construct(ICultureFeed $culturefeed) {
    $this->culturefeed = $culturefeed;
    $this->oauth_client = $culturefeed->getClient();
  }

  /**
   * @see CultureFeed_SavedSearches::subscribe().
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch) {
    $this->oauth_client->authenticatedPostAsXml('savedSearch/subscribe', $savedSearch->toPostData());
  }

}
