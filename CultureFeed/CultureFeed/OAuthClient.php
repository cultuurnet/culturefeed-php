<?php

interface CultureFeed_OAuthClient
{
    public function getConsumer(): OAuthConsumer;

    public function getToken(): OAuthConsumer;

    public function consumerGet(string $path, array $params = array(), string $format = '');

    public function consumerGetAsXml(string $path, array $params = array());

    public function consumerGetAsJson(string $path, array $params = array());

    public function consumerPost(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false,
        string $format = ''
    );

    public function consumerPostAsXml(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false
    );

    public function consumerPostAsJson(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false
    );

    public function authenticatedGet(string $path, array $params = array(), string $format = '');

    public function authenticatedGetAsXml(string $path, array $params = array());

    public function authenticatedGetAsJson(string $path, array $params = array());

    public function authenticatedPost(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false,
        string $format = ''
    );

    public function authenticatedPostAsXml(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false
    );

    public function authenticatedPostAsJson(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false
    );

    public function authenticatedDelete(string $path, array $params = array(), string $format = ''): CultureFeed_HttpResponse;

    public function authenticatedDeleteAsXml(string $path, array $params = array()): CultureFeed_HttpResponse;

    public function authenticatedDeleteAsJson(string $path, array $params = array()): CultureFeed_HttpResponse;

    public function request(
        string $path,
        array $params = array(),
        string $method = 'GET',
        bool $use_auth = true,
        string $format = 'xml',
        bool $raw_post = true, bool $has_file_upload = false
    ): CultureFeed_HttpResponse;

    public function getUrl(string $path, array $query = array()): string;

}
