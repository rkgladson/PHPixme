<?php
namespace tests\PHPixme;

use PHPixme as P;

class unaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\unary'
      , P\unary
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\unary)
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
      , P\unary($countArgs)
      , 'unary should return a closure'
    );

    $this->assertEquals(
      1
      , P\unary($countArgs)->__invoke(1, 2, 3)
      , 'Unary should eat all but one argument'
    );
  }
}
