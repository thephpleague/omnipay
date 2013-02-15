<?php

error_reporting(E_ALL | E_STRICT);

// include the composer autoloader and add our tests directory
$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Omnipay', __DIR__);
