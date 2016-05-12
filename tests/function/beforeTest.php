<?php
namespace tests\PHPixme;

use PHPixme as P;

class beforeTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\before'
      , P\before
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\before)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = 5)
  {
    $printArgs = P\before(function () {
      printf(json_encode(func_get_args()));
    });
    $expectingSideEffect = json_encode([$value]);
    $this->assertInstanceOf(
      Closure
      , $printArgs
      , 'The first argument shall produce a Closure'
    );
    $echoArgsIdiot = $printArgs(function ($x) {
      return $x;
    });
    $this->assertInstanceOf(
      Closure
      , $echoArgsIdiot
      , 'The second argument shall produce a closure'
    );

    $this->expectOutputString($expectingSideEffect . $expectingSideEffect);
    $this->assertTrue(
      $value === $echoArgsIdiot($value)
      , 'Before shalt not alter the output'
    );
    $this->assertTrue(
      ($value + 1) === $printArgs(function ($x) {
        return $x + 1;
      })->__invoke($value)
      , 'Before shalt not alter the input'
    );
  }
}
