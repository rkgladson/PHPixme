<?php
namespace tests\PHPixme;

use PHPixme as P;

class S_tarlingTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\S'
      , P\S
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\S)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $this->assertInstanceOf(
      Closure
      , P\S(P\I)
      , 'Starling should be able to be partially applied'
    );
    $this->assertEquals(
      $value
      , P\S(P\K)->__invoke(P\K($value))->__invoke($value)
      , 'S(K, K($value))($value) === $value is one of the more basic proofs to Starling'
    );

  }

  public function test_scenario_tupleMaker($array = [1, 2, 3, 4])
  {
    // Test to see if we can fix array_map through starling to get the key with the value
    $kvTupple = function ($v, $k) {
      return [$k, $v];
    };
    $kvMap = P\curry(3, P\ternary('array_map'))->__invoke($kvTupple);
    $this->assertEquals(
      array_map($kvTupple, $array, array_keys($array))
      , P\S($kvMap, 'array_keys')->__invoke($array)
    );
  }
}
