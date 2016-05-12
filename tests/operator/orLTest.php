<?php
namespace tests\PHPixme;

use PHPixme as P;

class orLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\orL'
      , P\orL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\orL)
      , 'assure the constant points to the function by the same name'
    );
    $orL = P\orL();
    $this->assertInstanceOf(
      Closure,
      $orL
      , 'the function applied with no arguments should result in the curried function'
    );
    $this->assertTrue($orL === $orL(), 'thunk identity');
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x || $y;
    $this->assertEquals(
      $expectedResult
      , P\orL($x, $y)
      , 'Immediate application'
    );
    $orL_XXY = P\orL(P\_(), $y);
    $this->assertInstanceOf(Closure, $orL_XXY, 'deferred flipped application');
    $this->assertTrue($orL_XXY === $orL_XXY(), 'thunk identity');
    $this->assertEquals($expectedResult, $orL_XXY($x));
    $this->assertEquals($expectedResult, $orL_XXY()->__invoke($x));

    $orLXX = P\orL($x);
    $this->assertInstanceOf(Closure, $orLXX, 'omission should be a closure');
    $this->assertTrue($orLXX === $orLXX(), 'thunk identity');
    $this->assertEquals($expectedResult, $orLXX($y));
    $this->assertEquals($expectedResult, $orLXX()->__invoke($y));

    $orLXX_Y = P\orL($x, P\_());
    $this->assertInstanceOf(Closure, $orLXX_Y, '2nd placeholder should be a closure');
    $this->assertTrue($orLXX_Y === $orLXX_Y(), 'thunk identity');
    $this->assertEquals($expectedResult, $orLXX_Y($y));
    $this->assertEquals($expectedResult, $orLXX_Y()->__invoke($y));

    // Torture the thunk.
    $orL = P\orL();
    $this->assertEquals($expectedResult, $orL($x, $y));
    $this->assertEquals($expectedResult, $orL(P\_(), $y)->__invoke($x));
    $this->assertEquals($expectedResult, $orL($x)->__invoke($y));
    $this->assertEquals($expectedResult, $orL($x, P\_())->__invoke($y));
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
