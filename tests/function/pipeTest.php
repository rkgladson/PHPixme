<?php
namespace tests\PHPixme;

use PHPixme as P;

class pipeTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pipe'
      , P\pipe
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\pipe)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $this->assertInstanceOf(
      Closure
      , P\pipe('array_reverse', 'json_encode')
      , 'pipe should return a closure'
    );

    $this->assertInstanceOf(
      Closure
      , P\combine('array_reverse')
      , 'pipe should be a curried function'
    );

    $array = [1, 2, 3];
    $this->assertEquals(
      json_encode(array_reverse($array))
      , P\pipe('array_reverse')->__invoke('json_encode')->__invoke($array)
      , 'pipe should be able to chain the outputs to produce hof results'
    );
    $this->assertEquals(
      json_decode(json_encode(array_reverse($array)))
      , P\pipe('array_reverse', 'json_encode', 'json_decode')->__invoke($array)
      , 'pipe should be able to handle more than two functions in the chain'
    );
  }
}
