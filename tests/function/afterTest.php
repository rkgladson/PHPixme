<?php
namespace tests\PHPixme;

use PHPixme as P;

class afterTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\after'
      , P\after
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\after)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = [5, 5])
  {
    $printResult = P\after('printf');
    $multiply = function ($x, $y) {
      return $x * $y;
    };
    $verboseMultiplication = $printResult($multiply);
    $output = call_user_func_array($multiply, $value);

    $this->assertInstanceOf(
      Closure
      , $printResult
      , 'The frist argument shal produce a Closure'
    );
    $this->assertInstanceOf(
      Closure
      , $verboseMultiplication
      , 'The secon argument shal produce a closure'
    );

    $this->expectOutputString((string)$output);
    $this->assertTrue(
      $output === call_user_func_array($verboseMultiplication, $value)
      , 'after shalt not alter the output'
    );
  }
}
