<?php
namespace tests\PHPixme;

use PHPixme as P;

class combineTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\combine'
      , P\combine
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\combine)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $this->assertInstanceOf(
      Closure
      , P\combine('json_encode', 'array_reverse')
      , 'combine should return a closure'
    );

    $this->assertInstanceOf(
      Closure
      , P\combine('json_encode')
      , 'combine should be a curried function'
    );

    $array = [1, 2, 3];
    $this->assertEquals(
      json_encode(array_reverse($array))
      , P\combine('json_encode')->__invoke('array_reverse')->__invoke($array)
      , 'combine should be able to chain the outputs to produce hof results'
    );
    $this->assertEquals(
      json_decode(json_encode(array_reverse($array)))
      , P\combine('json_decode', 'json_encode', 'array_reverse')->__invoke($array)
      , 'combine should be able to chain more than two callables and still produce results'
    );
  }
}
