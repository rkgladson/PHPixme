<?php
namespace tests\PHPixme;

use PHPixme as P;

class orBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\orB'
      , P\orB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\orB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\orB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x || $y;
    $this->assertEquals(
      $expectedResult
      , P\orB($x, $y)
      , 'Immediate application'
    );
    $orB_XXY = P\orB(P\_(), $y);
    $this->assertInstanceOf(Closure, $orB_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $orB_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [1, 1]
      , [1, 0]
      , [0, 1]
      , [0, 0]
    ];
  }
}
