<?php

namespace tests\PHPixme;

use PHPixme as P;

class ltTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\lt'
      , P\lt
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\lt)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\lt()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 0, $y = 1)
  {
    $expectedResult = $x < $y;
    $this->assertEquals(
      $expectedResult
      , P\lt($x, $y)
      , 'Immediate application'
    );
    $lt_XXY = P\lt(P\_(), $y);
    $this->assertInstanceOf(Closure, $lt_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $lt_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}