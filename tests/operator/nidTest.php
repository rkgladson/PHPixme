<?php
namespace tests\PHPixme;

use PHPixme as P;

class nidTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\nid'
      , P\nid
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\nid)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\nid()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = '')
  {
    $expectedResult = $x !== $y;
    $this->assertEquals(
      $expectedResult
      , P\nid($x, $y)
      , 'Immediate application'
    );
    $nid_XXY = P\nid(P\_(), $y);
    $this->assertInstanceOf(Closure, $nid_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $nid_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}