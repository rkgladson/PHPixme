<?php
namespace tests\PHPixme;

use PHPixme as P;

class xorBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\xorB'
      , P\xorB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\xorB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\xorB()
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
      , P\xorB($x, $y)
      , 'Immediate application'
    );
    $xorB_XXY = P\xorB(P\_(), $y);
    $this->assertInstanceOf(Closure, $xorB_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $xorB_XXY($x)
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
