<?php
namespace tests\PHPixme;

use PHPixme as P;

class KI_teTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\KI'
      , P\KI
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\KI)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true, $notValue = false)
  {
    $this->assertInstanceOf(
      Closure
      , P\KI($value)
      , 'KI should return a closure'
    );
    $this->assertEquals(
      $notValue
      , P\KI($value)->__invoke($notValue)
      , 'K resultant closure should ignore the constant and return the argument it recieves'
    );
  }
}
