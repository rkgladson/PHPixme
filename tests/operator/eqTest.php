<?php
namespace tests\PHPixme;

use PHPixme as P;

class eqTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\eq'
      , P\eq
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\eq)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\eq()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = 0)
  {
    $expectedResult = $x == $y;
    $this->assertEquals(
      $expectedResult
      , P\eq($x, $y)
      , 'Immediate application'
    );
    $eq_XXY = P\eq(P\_(), $y);
    $this->assertInstanceOf(Closure, $eq_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $eq_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}