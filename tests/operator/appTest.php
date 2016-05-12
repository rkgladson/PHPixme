<?php
namespace tests\PHPixme;

use PHPixme as P;

class appTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\app'
      , P\app
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\app)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\app()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = [1, 2, 3], $y = [4, 5, 6])
  {
    $expectedResult = $x + $y;
    $this->assertEquals(
      $expectedResult
      , P\app($x, $y)
      , 'Immediate application'
    );
    $app_XXY = P\app(P\_(), $y);
    $this->assertInstanceOf(Closure, $app_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $app_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
