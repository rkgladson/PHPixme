<?php
namespace tests\PHPixme;

use PHPixme as P;

class I_diotBirdTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\I'
      , P\I
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\I)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $this->assertEquals(
      $value
      , P\I($value)
      , 'The notoriously simple idiot bird proves useful in unusual places'
    );
  }
}
