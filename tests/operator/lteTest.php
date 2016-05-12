<?php
namespace tests\PHPixme;

use PHPixme as P;

class lteTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\lte'
      , P\lte
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\lte)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\lte()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($lhs = 0, $rhs = 1)
  {
    $expectedResult = $lhs <= $rhs;
    $this->assertEquals(
      $expectedResult
      , P\lte($lhs, $rhs)
      , 'Immediate application'
    );
    $lte_XXY = P\lte(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $lte_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $lte_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
