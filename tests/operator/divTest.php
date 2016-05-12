<?php
namespace tests\PHPixme;

use PHPixme as P;

class divTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\div'
      , P\div
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\div)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\div()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x / $y;
    $this->assertEquals(
      $expectedResult
      , P\div($x, $y)
      , 'Immediate application'
    );
    $div_XXY = P\div(P\_(), $y);
    $this->assertInstanceOf(Closure, $div_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $div_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
