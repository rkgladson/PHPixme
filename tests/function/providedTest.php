<?php
namespace tests\PHPixme;

use PHPixme as P;

class providedTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\provided'
      , P\provided
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\provided)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $truthy = function ($x) {
      return (boolean)$x;
    };
    $compliment = function ($x) use ($truthy) {
      return !$truthy($x);
    };
    $fn = function ($x) {
      return $x;
    };
    $providedTruthy = P\provided($truthy);
    $falsyIsNull = $providedTruthy($fn);
    $this->assertInstanceOf(
      Closure
      , $providedTruthy
      , 'the function should be curried'
    );
    $this->assertInstanceOf(
      Closure
      , $falsyIsNull
      , 'the function should return a closure.'
    );
    $this->assertTrue(
      $fn($value) === $falsyIsNull($value)
      , 'the function should return the value of the function when the predicate is true'
    );
    $this->assertNull(
      P\provided($compliment, $fn)->__invoke($value)
      , 'The return value for a predicate false is null'
    );
  }
}
