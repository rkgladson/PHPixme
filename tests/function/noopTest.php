<?php
namespace tests\PHPixme;

use PHPixme as P;

class noopTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\noop'
      , P\noop
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\noop)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $this->expectOutputString('');
    /** @noinspection PhpVoidFunctionResultUsedInspection */
    /** @noinspection PhpMethodParametersCountMismatchInspection */
    $this->assertNull(P\noop($value));
  }
}
