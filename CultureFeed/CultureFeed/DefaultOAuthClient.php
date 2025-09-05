<?php

class CultureFeed_DefaultOAuthClient implements CultureFeed_OAuthClient
{

    protected ?CultureFeed_HttpClient $http_client = null;

    protected string $endpoint = 'http://acc.uitid.be/uitid/rest/';

    protected OAuthSignatureMethod $signature_method;

    protected OAuthConsumer $consumer;

    protected OAuthConsumer $token;

    public function __construct(
        string $consumer_key,
        string $consumer_secret,
        string $oauth_token = null,
        string $oauth_token_secret = null
    )
    {
        $this->signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        if (!empty($oauth_token) && !empty($oauth_token_secret)) {
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
        }
    }

    public function setHttpClient(CultureFeed_HttpClient $http_client): void
    {
        $this->http_client = $http_client;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function getConsumer(): OAuthConsumer
    {
        return $this->consumer;
    }

    public function getToken(): OAuthConsumer
    {
        return $this->token;
    }

    public function consumerGet(string $path, array $params = array(), string $format = '')
    {
        return $this->request($path, $params, 'GET', FALSE, $format);
    }

    public function consumerGetAsXml(string $path, array $params = array())
    {
        return $this->consumerGet($path, $params, 'xml');
    }

    public function consumerGetAsJson(string $path, array $params = array())
    {
        return $this->consumerGet($path, $params, 'json');
    }

    public function consumerPost(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        $has_file_upload = false,
        string $format = ''
    )
    {
        return $this->request($path, $params, 'POST', FALSE, $format, $raw_post, $has_file_upload = FALSE);
    }

    public function consumerPostAsXml(
        string $path,
        array $params = array(),
        $raw_post = true,
        $has_file_upload = false
    )
    {
        return $this->consumerPost($path, $params, $raw_post, $has_file_upload, 'xml');
    }

    public function consumerPostAsJson(
        string $path,
        array $params = array(),
        $raw_post = true,
        $has_file_upload = false
    )
    {
        return $this->consumerPost($path, $params, $raw_post, $has_file_upload, 'json');
    }

    public function authenticatedGet(string $path, array $params = array(), string $format = '')
    {
        return $this->request($path, $params, 'GET', TRUE, $format);
    }

    public function authenticatedGetAsXml(string $path, array $params = array())
    {
        return $this->authenticatedGet($path, $params, 'xml');
    }

    public function authenticatedGetAsJson(string $path, array $params = array())
    {
        return $this->authenticatedGet($path, $params, 'json');
    }

    public function authenticatedPost(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false,
        string $format = ''
    )
    {
        return $this->request($path, $params, 'POST', TRUE, $format, $raw_post, $has_file_upload);
    }

    public function authenticatedPostAsXml(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = FALSE
    )
    {
        return $this->authenticatedPost($path, $params, $raw_post, $has_file_upload, 'xml');
    }

    public function authenticatedPostAsJson(
        string $path,
        array $params = array(),
        bool $raw_post = true,
        bool $has_file_upload = false
    )
    {
        return $this->authenticatedPost($path, $params, $raw_post, $has_file_upload, 'json');
    }

    public function authenticatedDelete(string $path, array $params = array(), string $format = '')
    {
        return $this->request($path, $params, 'DELETE', TRUE, $format);
    }

    public function authenticatedDeleteAsXml(string $path, array $params = array())
    {
        return $this->authenticatedDelete($path, $params, 'xml');
    }

    public function authenticatedDeleteAsJson(string $path, array $params = array())
    {
        return $this->authenticatedDelete($path, $params, 'json');
    }

    public function request(
        string $path,
        array $params = array(),
        string $method = 'GET',
        bool $use_auth = true,
        string $format = 'xml',
        bool $raw_post = true, bool $has_file_upload = false
    ): CultureFeed_HttpResponse {
        if ($use_auth && !isset($this->token->key)) {
            throw new Exception('Trying to do an authorized request without an access token set.');
        }

        // Getting full URL.
        $url = $this->getUrl($path);

        // Getting the request token for the request based on $use_auth.
        $request_token = $use_auth ? $this->token : NULL;

        // Since the OAuth library doesn't support multipart, we don't encode params that have a file.
        $params_to_encode = $has_file_upload ? array() : $params;

        // If raw data should be posted, don't encode it.
        $first_key = key($params);
        if ($first_key == 'raw_data') {
            $params_to_encode = array();
            $params = $params[$first_key];
        }

        // Constructing the request...
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $request_token, $method, $url, $params_to_encode);

        // ... and signing it.
        $request->sign_request($this->signature_method, $this->consumer, $request_token);

        // Getting the URL for the request.
        $url = $request->to_url();

        if ($method == 'POST') {
            $url = $request->get_normalized_http_url();
        } elseif ($method == 'DELETE') {
            $url = $this->getUrl($path, $params);
        }

        $http_headers = array();

        // Setting the OAuth headers.
        $http_headers[] = $request->to_header();

        // Setting the 'Accept' header.
        switch ($format) {
            case 'json':
                $http_headers[] = 'Accept: application/json';
                break;
            case 'xml':
                $http_headers[] = 'Accept: application/xml';
                break;
        }

        // Setting the 'Content-Type' header.
        if (is_string($params) && substr($params, 0, 5) == '<?xml') {
            $http_headers[] = 'Content-Type: application/xml; charset=UTF-8';
        }

        // If we have a file upload, we pass $params as an array to trigger CURL multipart.
        $post_data = (is_string($params) || $has_file_upload) ? $params : self::build_query($params);

        // Necessary to support token setup calls.
        if (!$raw_post) {
            $post_data = $request->to_postdata();
        }

        // If no HTTP client was set, create one.
        if (!isset($this->http_client)) {
            $this->http_client = new CultureFeed_DefaultHttpClient();
        }

        // Do the request.
        $response = $this->http_client->request($url, $http_headers, $method, $post_data);

        // In case the HTTP response status is not 200, we consider this an error.
        // In case we can parse a code and message from the response, we throw a CultureFeed_Exception.
        // In case we can't parse a code and message, we throw a CultureFeed_HTTPException.
        if ($response->code != 200) {

            try {
                $xml = new CultureFeed_SimpleXMLElement($response->response);
            } catch (Exception $e) {
                throw new CultureFeed_HttpException($response->response . ' URL CALLED: ' . $url . ' POST DATA: ' . $post_data . ' HTTP HEADERS: ' . implode(',', $http_headers), $response->code);
            }

            if ($code = $xml->xpath_str('/response/code')) {
                $message = $xml->xpath_str('/response/message');
                $exception_message = $message . ' URL CALLED: ' . $url . ' POST DATA: ' . $post_data;

                if ($code == CultureFeed_HttpResponse::ERROR_CODE_ACCESS_DENIED) {
                    $e = new CultureFeed_AccessDeniedException($exception_message, $code);
                    $e->requiredPermission = $xml->xpath_str('/response/requiredPermission');
                } else {
                    $e = new CultureFeed_Exception($exception_message, $code, $response->code);
                }

                if (!empty($message)) {
                    $e->setUserFriendlyMessage($message);
                }

                throw $e;
            }
            throw new CultureFeed_HttpException($response->response . ' URL CALLED: ' . $url . ' POST DATA: ' . $post_data . ' HTTP HEADERS: ' . implode(',', $http_headers), $response->code);
        }

        // In case the HTTP response status is 200, we return the response.
        return $response;
    }

    public function getUrl(string $path, array $query = array()): string
    {
        $url = rtrim($this->endpoint, '/') . '/' . trim($path, '/');

        if (!empty($query)) {
            $url .= '?' . self::build_query($query);
        }

        return $url;
    }

    private static function build_query(array $params): string
    {
        $parts = array();

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $value_part) {
                    $parts[] = urlencode($key) . '=' . urlencode($value_part);
                }
            } else {
                $parts[] = urlencode($key) . '=' . urlencode($value);
            }
        }

        return implode('&', $parts);
    }
}
