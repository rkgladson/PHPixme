<?php
namespace tests\PHPixme;

use PHPixme as P;

class notLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\notL'
      , P\notL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\notL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\notL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return()
  {
    $this->assertTrue(P\notL(false));
    $this->assertFalse(P\notL(true));
    $this->assertTrue(P\notL(P\_())->__invoke(false));
    $this->assertFalse(P\notL(P\_())->__invoke(true));

  }
}