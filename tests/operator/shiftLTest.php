<?php
namespace tests\PHPixme;

use PHPixme as P;

class shiftLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\shiftL'
      , P\shiftL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\shiftL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\shiftL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($lhs = 1, $rhs = 5)
  {
    $expectedResult = $lhs << $rhs;
    $this->assertEquals(
      $expectedResult
      , P\shiftL($lhs, $rhs)
      , 'Immediate application'
    );
    $shiftL_XXY = P\shiftL(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $shiftL_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $shiftL_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
