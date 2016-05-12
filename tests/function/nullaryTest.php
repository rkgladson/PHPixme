<?php
namespace tests\PHPixme;

use PHPixme as P;

class nullaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\nullary'
      , P\nullary
      , 'Ensure the constant is assigned to its function name'
    );

    $this->assertTrue(
      function_exists(P\nullary)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };

    $this->assertInstanceOf(
      Closure
      , P\nullary($countArgs)
      , 'nullary should return a closure'
    );

    $this->assertEquals(
      0
      , P\nullary($countArgs)->__invoke(1, 2, 3, 4)
      , 'nullary should eat all arguments'
    );
  }
}
