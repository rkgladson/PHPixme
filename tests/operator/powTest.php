<?php
namespace tests\PHPixme;

use PHPixme as P;

class powTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pow'
      , P\pow
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\pow)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\pow()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x ** $y;
    $this->assertEquals(
      $expectedResult
      , P\pow($x, $y)
      , 'Immediate application'
    );
    $pow_XXY = P\pow(P\_(), $y);
    $this->assertInstanceOf(Closure, $pow_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $pow_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
