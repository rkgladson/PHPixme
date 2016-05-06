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
  use PHPixme\AssertTypeTrait;
  use PHPixme\ClosedTrait;
  const Closure = \Closure::class;

  function getArgs()
  {
    return func_get_args();
  }

  class AssertTypeStub
  {
    use AssertTypeTrait;
  }

  /**
   * Class CloseTypeStub
   * @property void $exceptionFoo
   * @package tests\PHPixme
   */
  class CloseTypeStub {
    use ClosedTrait;
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

  class JustAIterator implements \Iterator{
    private $data = [];
    private $valid = true;

    public function current()
    {
      return current($this->data);
    }

    public function next()
    {
       $this->valid = false !== each($this->data);
    }

    public function key()
    {
      return key($this->data);
    }

    public function valid()
    {
      return $this->valid;
    }

    public function rewind()
    {
      reset($this->data);
    }

    public function __construct(array $data)
    {
      $this->data = $data;
    }
  }

  function getAllTraits (\ReflectionClass $reflection) {
    return array_merge($reflection->getTraitNames(), $reflection->getParentClass() !== false ? getAllTraits($reflection->getParentClass()) : []);
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
