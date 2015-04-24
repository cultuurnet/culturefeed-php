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
    $result = $this->oauth_client->authenticatedPostAsXml('savedSearch/subscribe', $savedSearch->toPostData());
    $xml_element = $this->getXmlElementFromXmlString($result);

    $search_element = $xml_element->xpath('/response/savedSearch');

    if (empty($search_element)) {
      $this->throwXmlElementException($xml_element, $result);
    }

    return $this->parseSavedSearch($search_element[0]);
  }

  /**
   * {@inheritdoc}
   */
  public function unsubscribe($savedSearchId, $userId) {
    $result = $this->oauth_client->authenticatedPostAsXml(
      'savedSearch/' . $savedSearchId . '/unsubscribe',
      array('userId' => $userId)
    );

    $xml_element = $this->getXmlElementFromXmlString($result);

    $search_element = $xml_element->xpath('savedSearch');

    if (empty($search_element)) {
      $this->throwXmlElementException($xml_element, $result);
    }

    return $xml_element->xpath_str('message');
  }

  /**
   * {@inheritdoc}
   */
  public function changeFrequency($savedSearchId, $frequency) {
    $result = $this->oauth_client->authenticatedPostAsXml(
      'savedSearch/' . $savedSearchId . '/frequency',
      array('frequency' => $frequency)
    );

    $xml_element = $this->getXmlElementFromXmlString($result);

    $search_element = $xml_element->xpath('/response/savedSearch');

    if (empty($search_element)) {
      $this->throwXmlElementException($xml_element, $result);
    }

    return $this->parseSavedSearch($search_element[0]);
  }

  /**
   * {@inheritdoc}
   */
  public function getSavedSearch($savedSearchId) {
    $result = $this->oauth_client->authenticatedGetAsXml('savedSearch/' . $savedSearchId);
    $xml_element = $this->getXmlElementFromXmlString($result);

    $search_element = $xml_element->xpath('savedSearch');

    if (empty($search_element)) {
      $this->throwXmlElementException($xml_element, $result);
    }

    return $this->parseSavedSearch($search_element[0]);
  }

  /**
   * {@inheritdoc}
   */
  public function getList($allConsumers = false) {
    $allConsumers = $allConsumers ? 'true' : 'false';
    $result = $this->oauth_client->authenticatedGetAsXml(
      'savedSearch/list',
      array('all' => $allConsumers)
    );

    $xml_element = $this->getXmlElementFromXmlString($result);
    $saved_searches = array();

    $search_elements = $xml_element->xpath('/response/savedSearches/savedSearch');
    if (empty($search_elements)) {
      $this->throwXmlElementException($xml_element, $result);
    }
    foreach ($search_elements as $search_element) {
      $search = $this->parseSavedSearch($search_element);
      $saved_searches[$search->id] = $search;
    }

    return $saved_searches;
  }

  /**
   * Parse a saved search.
   * @param CultureFeed_SimpleXMLElement $xml_element
   * @return CultureFeed_SavedSearches_SavedSearch
   */
  private function parseSavedSearch($xml_element) {
    $search = new CultureFeed_SavedSearches_SavedSearch();
    $search->id = $xml_element->xpath_int('id');
    $search->frequency = $xml_element->xpath_str('frequency');
    $search->name = $xml_element->xpath_str('name');
    $search->query = $xml_element->xpath_str('query');
    $search->userId = $xml_element->xpath_str('uitIdUser/rdf:id');

    return $search;
  }

  /**
   * Create an XML element from a given XML string.
   * @param string $data
   *   A well-formed XML string.
   * @return CultureFeed_SimpleXMLElement
   * @throws CultureFeed_ParseException
   *   If the data could not be parsed.
   */
  private function getXmlElementFromXmlString($data) {
    try {
      $xml_element = new CultureFeed_SimpleXMLElement($data);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($data);
    }

    return $xml_element;
  }

  /**
   * Throw an exception based on an xml element content.
   * @param CultureFeed_SimpleXMLElement $xml_element
   *   A parsed version of the result.
   * @param string $result
   *   The result from a callback to include in the exception message.
   * @throws \CultureFeed_Exception
   *   If the XML element contains an error code.
   * @throws \CultureFeed_ParseException
   *   If the XML element does not contain an error code.
   */
  private function throwXmlElementException($xml_element, $result) {
    if ($error_code = $xml_element->xpath_str('code') && $error_message = $xml_element->xpath_str('message')) {
      throw new CultureFeed_Exception($error_message, $error_code);
    } else {
      throw new CultureFeed_ParseException($result);
    }
  }

}
