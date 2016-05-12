<?php
namespace tests\PHPixme;

use PHPixme as P;

class ternaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\ternary'
      , P\ternary
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\ternary)
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
      , P\ternary($countArgs)
      , 'ternary should return a closure'
    );

    $this->assertEquals(
      3
      , P\ternary($countArgs)->__invoke(1, 2, 3, 4)
      , 'ternary should eat all but three arguments'
    );
  }
}
