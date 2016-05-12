<?php
namespace tests\PHPixme;

use PHPixme as P;

class subTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\sub'
      , P\sub
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\sub)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\sub()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x - $y;
    $this->assertEquals(
      $expectedResult
      , P\sub($x, $y)
      , 'Immediate application'
    );
    $sub_XXY = P\sub(P\_(), $y);
    $this->assertInstanceOf(Closure, $sub_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $sub_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
