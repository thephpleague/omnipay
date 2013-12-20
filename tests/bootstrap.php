<?php

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('UTC');

// include the composer autoloader
$autoloader = require __DIR__.'/../vendor/autoload.php';

// autoload abstract TestCase classes in test directory
$autoloader->add('Omnipay', __DIR__);
