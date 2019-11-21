<?php

if (file_exists('vendor/autoload.php')) {
  require_once 'vendor/autoload.php';
}

require_once 'BackwardsCompatiblePHPUnitTestCase.php';

date_default_timezone_set('Europe/Brussels');

class_alias(BackwardsCompatiblePHPUnitTestCase::class, 'PHPUnit_Framework_TestCase');
