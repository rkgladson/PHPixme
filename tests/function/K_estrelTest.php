<?php
namespace tests\PHPixme;

use PHPixme as P;

class K_estrelTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\K'
      , P\K
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\K)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true, $notValue = false)
  {
    $this->assertInstanceOf(
      Closure
      , P\K($value)
      , 'K should return a closure'
    );
    $this->assertEquals(
      $value
      , P\K($value)->__invoke($notValue)
      , 'K resultant closure should return the constant that has been closed'
    );
  }
}
