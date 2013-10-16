<?php

$loader = require_once __DIR__ . "/../../vendor/autoload.php";
$loader->add('PublicVendor\\', __DIR__);

define('PROJECT_PATH',  realpath(__DIR__.'/../..'));
define('VENDOR_PATH', 	PROJECT_PATH.'/vendor');