<?php
namespace tests\PHPixme;

use PHPixme as P;

class toClosureTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\toClosure'
      , P\toClosure
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\toClosure)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider returnProvider
   */
  public function test_return(callable $callable, array $io)
  {
    $closure = P\toClosure($callable);
    $this->assertInstanceOf(Closure, $closure);
    $this->assertEquals(
      $io
      , call_user_func_array($closure, $io)
      , 'the function applyed to functions wich return thier arugments should return an identity'
    );
  }

  public function test_identity()
  {
    $closure = function () {
    };
    $this->assertTrue(
      P\toClosure($closure) === $closure
      , 'function applyed on Closure should return the idenity of that closure'
    );
  }

  public function returnProvider()
  {
    $testClass = new TestClass();
    $io = [1, 2, 3, 'narf!'];
    return [
      'static' => [TestClass::class . '::testStatic', $io]
      , 'Method instance' => [[$testClass, 'getArgs'], $io]
      , 'Static Method on instance' => [[$testClass, 'testStatic'], $io]
      , 'PHP Function' => [__NAMESPACE__ . '\getArgs', $io]
    ];
  }

}
