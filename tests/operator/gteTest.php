<?php

namespace tests\PHPixme;

use PHPixme as P;

class gteTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\gte'
      , P\gte
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\gte)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\gte()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 0, $y = 1)
  {
    $expectedResult = $x >= $y;
    $this->assertEquals(
      $expectedResult
      , P\gte($x, $y)
      , 'Immediate application'
    );
    $gte_XXY = P\gte(P\_(), $y);
    $this->assertInstanceOf(Closure, $gte_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $gte_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}