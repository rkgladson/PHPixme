<?php
namespace tests\PHPixme;

use PHPixme as P;

class andBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\andB'
      , P\andB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\andB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\andB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x && $y;
    $this->assertEquals(
      $expectedResult
      , P\andB($x, $y)
      , 'Immediate application'
    );
    $andB_XXY = P\andB(P\_(), $y);
    $this->assertInstanceOf(Closure, $andB_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $andB_XXY($x)
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
