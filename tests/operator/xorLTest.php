<?php
namespace tests\PHPixme;

use PHPixme as P;

class xorLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\xorL'
      , P\xorL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\xorL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\xorL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = ($x xor $y);

    $this->assertEquals(
      $expectedResult
      , P\xorL($x, $y)
      , 'Immediate application'
    );
    $xorL_XXY = P\xorL(P\_(), $y);
    $this->assertInstanceOf(Closure, $xorL_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $xorL_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [true, true]
      , [true, false]
      , [false, true]
      , [false, false]
    ];
  }
}
