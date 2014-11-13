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
   * {@inheritdoc}
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch) {
    $this->oauth_client->authenticatedPostAsXml('savedSearch/subscribe', $savedSearch->toPostData());
  }

  /**
   * {@inheritdoc}
   */
  public function getList($allConsumers = FALSE) {

    $result = $this->oauth_client->authenticatedGetAsXml('savedSearch/list', array('all' => $allConsumers ? 'true' : 'false'));
    try {
      $xmlElement = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $savedSearches = array();

    $searchElements = $xmlElement->xpath('/response/savedSearches/savedSearch');
    foreach ($searchElements as $searchElement) {

      $search = new CultureFeed_SavedSearches_SavedSearch();
      $search->id = $searchElement->xpath_int('id');
      $search->frequency = $searchElement->xpath_str('frequency');
      $search->name = $searchElement->xpath_str('name');
      $search->query = $searchElement->xpath_str('query');

      $savedSearches[$search->id] = $search;

    }

    return $savedSearches;

  }

}
