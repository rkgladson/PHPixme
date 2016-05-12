<?php
namespace tests\PHPixme;

use PHPixme as P;

class neqTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\neq'
      , P\neq
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\neq)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\neq()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = 0)
  {
    $expectedResult = $x != $y;
    $this->assertEquals(
      $expectedResult
      , P\neq($x, $y)
      , 'Immediate application'
    );
    $neq_XXY = P\neq(P\_(), $y);
    $this->assertInstanceOf(Closure, $neq_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $neq_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}