<?php
namespace tests\PHPixme;

use PHPixme as P;

class addTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\add'
      , P\add
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\add)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\add()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x + $y;
    $this->assertEquals(
      $expectedResult
      , P\add($x, $y)
      , 'Immediate application'
    );
    $add_XXY = P\add(P\_(), $y);
    $this->assertInstanceOf(Closure, $add_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $add_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}