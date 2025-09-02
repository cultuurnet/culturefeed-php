<?php

/**
 * A mock store for testing
 */
class Mock_OAuthDataStore extends OAuthDataStore {
	private $consumer;
	private $request_token;
	private $access_token;
	private $nonce;

	function __construct() {
		$this->consumer = new OAuthConsumer("key", "secret", NULL);
		$this->request_token = new OAuthToken("requestkey", "requestsecret");
		$this->access_token = new OAuthToken("accesskey", "accesssecret");
		$this->nonce = "nonce";
	}

	function lookup_consumer($consumer_key): ?string {
		if ($consumer_key == $this->consumer->key) return $this->consumer;
		return NULL;
	}

	function lookup_token($consumer, $token_type, $token): ?string {
		$token_attrib = $token_type . "_token";
		if ($consumer->key == $this->consumer->key
			&& $token == $this->$token_attrib->key) {
			return $this->$token_attrib;
		}
		return NULL;
	}

	function lookup_nonce($consumer, $token, $nonce, $timestamp): ?string {
		if ($consumer->key == $this->consumer->key
			&& (($token && $token->key == $this->request_token->key)
				|| ($token && $token->key == $this->access_token->key))
			&& $nonce == $this->nonce) {
			return $this->nonce;
		}
		return NULL;
	}

	function new_request_token($consumer, $callback = null): ?string {
		if ($consumer->key == $this->consumer->key) {
			return $this->request_token;
		}
		return NULL;
	}

	function new_access_token($token, $consumer, $verifier = null): ?string {
		if ($consumer->key == $this->consumer->key
			&& $token->key == $this->request_token->key) {
			return $this->access_token;
		}
		return NULL;
	}
}