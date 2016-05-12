<?php
namespace tests\PHPixme;

use PHPixme as P;

class catTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\cat'
      , P\cat
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\cat)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\cat()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = '1', $y = '2')
  {
    $expectedResult = $x . $y;
    $this->assertEquals(
      $expectedResult
      , P\cat($x, $y)
      , 'Immediate application'
    );
    $cat_XXY = P\cat(P\_(), $y);
    $this->assertInstanceOf(Closure, $cat_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $cat_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
