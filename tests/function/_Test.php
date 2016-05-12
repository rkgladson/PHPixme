<?php

namespace tests\PHPixme;

use PHPixme as P;

class _Test extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertInstanceOf(
      \stdClass::class
      , P\_()
      , 'the placeholder should be an instance of standard class'
    );
    $this->assertTrue(
      P\_() === P\_()
      , 'the placeholder value should not change between executions.'
    );
  }
}