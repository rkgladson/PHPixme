<?php
namespace tests\PHPixme;

use PHPixme as P;

class ufoTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\ufo'
      , P\ufo
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\ufo)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\ufo()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider returnProvider
   */
  public function test_return($lhs = 0, $rhs = 1)
  {

//    $expectedResult = $x <=> $y;
    $expectedResult = $lhs > $rhs ? 1 : ($lhs < $rhs ? -1 : 0);

    $this->assertEquals(
      $expectedResult
      , P\ufo($lhs, $rhs)
      , 'Immediate application'
    );
    $this->assertTrue(is_int(P\ufo($lhs, $rhs)));
    $ufo_XXY = P\ufo(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $ufo_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $ufo_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
    $this->assertTrue(is_int($ufo_XXY($lhs)));
  }

  public function returnProvider()
  {
    return [
      ['', 1]
      , ['1', 0]
      , ['0', 1]
      , [1.0, 1]
      , ['a', 'aa']
    ];
  }
}