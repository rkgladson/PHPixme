<?php
namespace tests\PHPixme;

use PHPixme as P;

class mulTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\mul'
      , P\mul
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\mul)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\mul()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x * $y;
    $this->assertEquals(
      $expectedResult
      , P\mul($x, $y)
      , 'Immediate application'
    );
    $mul_XXY = P\mul(P\_(), $y);
    $this->assertInstanceOf(Closure, $mul_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $mul_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
