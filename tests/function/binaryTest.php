<?php
namespace tests\PHPixme;

use PHPixme as P;

class binaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\binary'
      , P\binary
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\binary)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };

    $this->assertInstanceOf(
      Closure
      , P\binary($countArgs)
      , 'binary should return a closure'
    );

    $this->assertEquals(
      2
      , P\binary($countArgs)->__invoke(1, 2, 3)
      , 'binary should eat all but two arguments'
    );
  }
}
