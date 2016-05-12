<?php
namespace tests\PHPixme;

use PHPixme as P;

class modTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\mod'
      , P\mod
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\mod)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\mod()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x % $y;
    $this->assertEquals(
      $expectedResult
      , P\mod($x, $y)
      , 'Immediate application'
    );
    $mod_XXY = P\mod(P\_(), $y);
    $this->assertInstanceOf(Closure, $mod_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $mod_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
