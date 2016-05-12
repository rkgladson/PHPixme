<?php
namespace tests\PHPixme;

use PHPixme as P;

class nAryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\nAry'
      , P\nAry
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\nAry)
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
      , P\nAry(1)
      , 'nAry should be partially applied'
    );
    $this->assertEquals(
      1
      , P\nAry(1)->__invoke($countArgs)->__invoke(1, 2, 3)
      , 'nAry Partially applied should still produce a wrapped function that eats arguments'
    );
    $this->assertInstanceOf(
      Closure
      , P\nAry(1, $countArgs)
      , 'nAry fully applied should produce a closure'
    );
    $this->assertEquals(
      1
      , P\nAry(1, $countArgs)->__invoke(1, 2, 3, 4)
      , 'fully applied should still work the same as partially applied, eating arguments'
    );
  }
}
