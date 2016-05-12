<?php
namespace tests\PHPixme;

use PHPixme as P;

class andLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\andL'
      , P\andL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\andL)
      , 'assure the constant points to the function by the same name'
    );
    $andL = P\andL();
    $this->assertInstanceOf(
      Closure,
      $andL
      , 'the function applied with no arguments should result in the curried function'
    );
    $this->assertTrue($andL === $andL(), 'thunk identity');
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x && $y;
    $this->assertEquals(
      $expectedResult
      , P\andL($x, $y)
      , 'Immediate application'
    );
    $andL_XXY = P\andL(P\_(), $y);
    $this->assertInstanceOf(Closure, $andL_XXY, 'deferred flipped application');
    $this->assertTrue($andL_XXY === $andL_XXY(), 'thunk identity');
    $this->assertEquals($expectedResult, $andL_XXY($x));
    $this->assertEquals($expectedResult, $andL_XXY()->__invoke($x));

    $andLXX = P\andL($x);
    $this->assertInstanceOf(Closure, $andLXX, 'omission should be a closure');
    $this->assertTrue($andLXX === $andLXX(), 'thunk identity');
    $this->assertEquals($expectedResult, $andLXX($y));
    $this->assertEquals($expectedResult, $andLXX()->__invoke($y));

    $andLXX_Y = P\andL($x, P\_());
    $this->assertInstanceOf(Closure, $andLXX_Y, '2nd placeholder should be a closure');
    $this->assertTrue($andLXX_Y === $andLXX_Y(), 'thunk identity');
    $this->assertEquals($expectedResult, $andLXX_Y($y));
    $this->assertEquals($expectedResult, $andLXX_Y()->__invoke($y));

    // Torture the thunk.
    $andL = P\andL();
    $this->assertEquals($expectedResult, $andL($x, $y));
    $this->assertEquals($expectedResult, $andL(P\_(), $y)->__invoke($x));
    $this->assertEquals($expectedResult, $andL($x)->__invoke($y));
    $this->assertEquals($expectedResult, $andL($x, P\_())->__invoke($y));
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