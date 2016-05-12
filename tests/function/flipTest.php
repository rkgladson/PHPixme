<?php
namespace tests\PHPixme;

use PHPixme as P;

class flipTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\flip'
      , P\flip
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\flip)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $getArgs = function () {
      return func_get_args();
    };
    $this->assertInstanceOf(
      Closure
      , P\flip($getArgs)
      , 'Flip should return a closure'
    );

    $this->assertEquals(
      [2, 1, 3, 4, 5]
      , P\flip($getArgs)->__invoke(1, 2, 3, 4, 5)
      , 'Flip should flip the first two arguments'
    );
    $this->assertInstanceOf(
      Closure
      , P\flip($getArgs)->__invoke(1)
      , 'Flip partially applied should return a closure'
    );

    $this->assertEquals(
      [2, 1, 3, 4, 5]
      , P\flip($getArgs)->__invoke(1)->__invoke(2, 3, 4, 5)
      , 'Flip partially applied should return the flipped arguments'
    );
  }
}
