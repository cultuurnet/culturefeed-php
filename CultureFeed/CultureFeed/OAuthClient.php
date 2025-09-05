<?php

/**
 * Interface to represent a OAuth request.
 */
interface CultureFeed_OAuthClient {
  public function getConsumer(): OAuthConsumer;

  public function getToken(): OAuthConsumer;

  public function consumerGet(string $path, array $params = array(), string $format = '');

  public function consumerGetAsXml(string $path, array $params = array());

  public function consumerGetAsJson(string $path, array $params = array());

  /**
   * Do a POST request with only a consumer token.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function consumerPost($path, array $params = array(), $raw_post = TRUE, $has_file_upload = FALSE, $format = '');

  /**
   * Do a POST request with only a consumer token and return the response as XML.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function consumerPostAsXml($path, array $params = array(), $raw_post = TRUE, $has_file_upload = FALSE);

  /**
   * Do a POST request with only a consumer token and return the response as JSON.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function consumerPostAsJson($path, array $params = array(), $raw_post = TRUE, $has_file_upload = FALSE);

  /**
   * Do a GET request with a consumer token and user token.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedGet($path, array $params = array(), $format = '');

  /**
   * Do a GET request with a consumer token and user token and return the response as XML.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedGetAsXml($path, array $params = array());

  /**
   * Do a GET request with a consumer token and user token and return the response as JSON.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedGetAsJson($path, array $params = array());

  /**
   * Do a POST request with a consumer token and user token.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedPost($path, array $params = array(), $raw_post = TRUE, $has_file_upload = FALSE, $format = '');

  /**
   * Do a POST request with a consumer token and user token and return the response as XML.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedPostAsXml($path, array $params = array(), $raw_post = TRUE, $has_file_upload = FALSE);

  /**
   * Do a POST request with a consumer token and user token and return the response as JSON.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedPostAsJson($path, array $params = array(), $raw_post = TRUE, $has_file_upload = FALSE);

  public function authenticatedDelete(string $path, array $params = array(), string $format = '');

  /**
   * Do a DELETE request with a consumer token and user token and return the response as XML.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedDeleteAsXml($path, array $params = array());

  /**
   * Do a DELETE request with a consumer token and user token and return the response as JSON.
   *
   * Wrapper function around request. @see request for documentation of remaining parameters.
   */
  public function authenticatedDeleteAsJson($path, array $params = array());

  public function request(
      string $path,
      array $params = array(),
      string $method = 'GET',
      bool $use_auth = true,
      string $format = 'xml',
      bool $raw_post = true, bool $has_file_upload = false
  );

  public function getUrl(string $path, array $query = array()): string;

}