<?php
namespace tests\PHPixme;

use PHPixme as P;

class idTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\id'
      , P\id
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\id)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\id()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = '')
  {
    $expectedResult = $x === $y;
    $this->assertEquals(
      $expectedResult
      , P\id($x, $y)
      , 'Immediate application'
    );
    $id_XXY = P\id(P\_(), $y);
    $this->assertInstanceOf(Closure, $id_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $id_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}