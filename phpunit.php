<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/8/2016
 * Time: 11:18 AM
 */

error_reporting(E_ALL);
$autoloader = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
  echo "Composer autoloader not found: $autoloader" . PHP_EOL;
  echo "Please issue 'composer install' and try again." . PHP_EOL;
  exit(1);
}
require $autoloader;
require __DIR__ . '/phpunit/TestClass.php';
require __DIR__ . '/phpunit/AssertTypeStub.php';