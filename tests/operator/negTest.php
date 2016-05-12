<?php
namespace tests\PHPixme;

use PHPixme as P;

class negTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\neg'
      , P\neg
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\neg)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\neg()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1)
  {
    $expectedResult = -$x;
    $this->assertEquals(
      $expectedResult
      , P\neg($x)
      , 'Immediate application'
    );
    $neg_X = P\neg(P\_());
    $this->assertInstanceOf(Closure, $neg_X, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $neg_X($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}
