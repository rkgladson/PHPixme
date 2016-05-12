<?php
namespace tests\PHPixme;

use PHPixme as P;

class shiftRTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\shiftR'
      , P\shiftR
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\shiftR)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\shiftR()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($lhs = 1, $rhs = 5)
  {
    $expectedResult = $lhs >> $rhs;
    $this->assertEquals(
      $expectedResult
      , P\shiftR($lhs, $rhs)
      , 'Immediate application'
    );
    $shiftR_XXY = P\shiftR(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $shiftR_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $shiftR_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
