<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/8/2016
 * Time: 11:18 AM
 */
// All errors on
namespace {
  error_reporting(E_ALL);
}
// Define some dummy test functions that can be shared
namespace tests\PHPixme {
  use PHPixme\AssertType;
  const Closure = \Closure::class;
  require __DIR__ . '/phpunit/TestClass.php';

  function getArgs()
  {
    return func_get_args();
  }

  class AssertTypeStub
  {
    use AssertType;
  }

  class TestClass
  {
    public $value = true;

    static public function testStatic()
    {
      return func_get_args();
    }

    public function getArgs()
    {
      return func_get_args();
    }

    public function countArgs()
    {
      return func_num_args();
    }
  }

  class invokable
  {
    public function __invoke($x)
    {
      return $x;
    }
  }
}
// Load the autoload file.
namespace {
  $autoloader = __DIR__ . '/vendor/autoload.php';
  if (!file_exists($autoloader)) {
    echo "Composer autoloader not found: $autoloader" . PHP_EOL;
    echo "Please issue 'composer install' and try again." . PHP_EOL;
    exit(1);
  }
  require $autoloader;
}
