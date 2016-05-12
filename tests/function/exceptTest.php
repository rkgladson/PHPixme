<?php
namespace tests\PHPixme;

use PHPixme as P;

class exceptTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\except'
      , P\except
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\except)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $truthy = function ($x) {
      return (boolean)$x;
    };
    $compliment = function ($x) use ($truthy) {
      return !($truthy($x));
    };
    $fn = function ($x) {
      return $x;
    };
    $exceptTruthy = P\except($truthy);
    $truthyIsNull = $exceptTruthy($fn);
    $falselyIsNull = P\except($compliment, $fn);
    $this->assertInstanceOf(
      Closure
      , $exceptTruthy
      , 'the function should be curried'
    );
    $this->assertInstanceOf(
      Closure
      , $truthyIsNull
      , 'the function should return a closure.'
    );
    $this->assertTrue(
      $falselyIsNull($value) === $fn($value)
      , 'the function should return the value of the function when the predicate is false'
    );
    $this->assertNull(
      $truthyIsNull($value)
      , 'The return value for a predicate true is null'
    );
  }
}
