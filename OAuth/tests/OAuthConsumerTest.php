<?php

use PHPUnit\Framework\TestCase;

require 'common.php';

class OAuthConsumerTest extends TestCase {
	public function testConvertToString() {
		$consumer = new OAuthConsumer('key', 'secret');
		$this->assertEquals('OAuthConsumer[key=key,secret=secret]', (string) $consumer);
	}
}