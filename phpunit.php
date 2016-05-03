<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/8/2016
 * Time: 11:18 AM
 */
namespace {
  error_reporting(E_ALL);
}

namespace tests\PHPixme {
  const Closure = \Closure::class;
  require __DIR__ . '/phpunit/TestClass.php';
  require __DIR__ . '/phpunit/AssertTypeStub.php';
}

namespace {
  $autoloader = __DIR__ . '/vendor/autoload.php';
  if (!file_exists($autoloader)) {
    echo "Composer autoloader not found: $autoloader" . PHP_EOL;
    echo "Please issue 'composer install' and try again." . PHP_EOL;
    exit(1);
  }
  require $autoloader;
}
