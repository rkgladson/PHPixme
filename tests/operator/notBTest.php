<?php
namespace tests\PHPixme;

use PHPixme as P;

class notBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\notB'
      , P\notB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\notB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\notB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return()
  {
    $resultFalse = ~1;
    $resultTrue = ~0;
    $this->assertEquals($resultTrue, P\notB(0));
    $this->assertEquals($resultFalse, P\notB(1));
    $this->assertEquals($resultTrue, P\notB(P\_())->__invoke(0));
    $this->assertEquals($resultFalse, P\notB(P\_())->__invoke(1));

  }
}
