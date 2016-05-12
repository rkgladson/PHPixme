<?php
namespace tests\PHPixme;

use PHPixme as P;

class gtTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\gt'
      , P\gt
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\gt)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\gt()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 0, $y = 1)
  {
    $expectedResult = $x > $y;
    $this->assertEquals(
      $expectedResult
      , P\gt($x, $y)
      , 'Immediate application'
    );
    $gt_XXY = P\gt(P\_(), $y);
    $this->assertInstanceOf(Closure, $gt_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $gt_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
