<?php
namespace tests\PHPixme;

use PHPixme as P;

class tapTest extends \PHPUnit_Framework_TestCase
{
  public function test_tap_constant()
  {
    $this->assertStringEndsWith(
      '\tap'
      , P\tap
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\tap)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_tap($value = 1)
  {
    $consoleLog = P\tap('printf');
    $this->assertInstanceOf(
      Closure
      , $consoleLog
      , 'Tap should return a closure'
    );
    $this->expectOutputString((string)$value);
    $this->assertTrue(
      $consoleLog($value) === $value
      , 'Tap should not modify the value that passes through it.'
    );
  }
}
