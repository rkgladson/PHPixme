<?php
namespace tests\PHPixme;

use PHPixme as P;

class reduceTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\reduce'
      , P\reduce
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\reduce)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider undefinedBehaviorProvider
   * @expectedException \LengthException
   */
  public function test_contract_violation($arrayLike = [])
  {
    P\reduce(P\I, $arrayLike);
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $firstVal, $expVal, $expKey)
  {
    P\reduce(function () use ($firstVal, $expVal, $expKey, $arrayLike) {
      $this->assertEquals(
        4
        , func_num_args()
        , 'callback should receive four arguments'
      );
      $this->assertEquals(
        $firstVal
        , func_get_arg(0)
        , 'callback $prevVal should equal startValue'
      );
      $this->assertEquals(
        $expVal
        , func_get_arg(1)
        , 'callback should equal to expected value'
      );
      $this->assertEquals(
        $expKey
        , func_get_arg(2)
        , 'callback $key should equal to expected key'
      );
      if (is_object($arrayLike)) {
        $this->assertTrue(
          $arrayLike === func_get_arg(3)
          , 'callback $container should be the same instance as the source data'
        );
      } else {
        $this->assertEquals(
          $arrayLike
          , func_get_arg(3)
          , '$container should equal to the array like'
        );
      }

      return func_get_arg(0);
    }, $arrayLike);
  }

  public function test_return($array = [1, 2, 3, 4])
  {
    $this->assertInstanceOf(
      Closure
      , P\reduce(P\I)
      , 'when partially applied should return a closure'
    );
    $this->assertEquals(
      $array[0]
      , P\reduce(P\I)->__invoke($array)
      , 'An idiot applied should always return the start value'
    );
    $this->assertEquals(
      $array[count($array) - 1]
      , P\reduce(P\flip(P\I), $array)
      , 'The flipped idiot applied should always return the last'
    );
  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\reduce(P\I, $test);
    $this->assertTrue(
      $prevKey === $test->key()
      , 'The function must not alter the state of an iterator.'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $action, $expected)
  {
    $this->assertEquals(
      $expected
      , P\reduce($action, $arrayLike)
    );
  }

  public function undefinedBehaviorProvider()
  {
    return [
      '[]' => [[]]
      , 'None' => [P\None()]
      , 'S[]' => [P\Seq::of()]
      , 'ArrayObject[]' => [new \ArrayObject([])]
      , 'ArrayIterator[]' => [new \ArrayIterator([])]
    ];
  }

  public function callbackProvider()
  {
    return [
      'array callback' => [
        [1, 2], 1, 2, 1
      ]
      , 'iterator aggregate callback' => [
        new \ArrayObject([1, 2]), 1, 2, 1
      ]
      , 'iterator callback' => [
        new \ArrayIterator([1, 2]), 1, 2, 1
      ]
      , 'natural interface callback' => [
        P\Seq::of(1, 2), 1, 2, 1
      ]
    ];
  }

  public function scenarioProvider()
  {
    $add = function ($a, $b) {
      return $a + $b;
    };
    return [
      'add 1' => [
        [1]
        , $add
        , 1
      ]
      , 'add S[1]' => [
        P\Seq::of(1)
        , $add
        , 1
      ]

      , 'add ArrayObject[1]' => [
        new \ArrayIterator([1])
        , $add
        , 1
      ]
      , 'add Some(2)' => [
        P\Some(2)
        , $add
        , 2
      ]
      , 'add 1+2+3' => [
        [1, 2, 3]
        , $add
        , 6
      ]
      , 'add S[1,2,3]' => [
        P\Seq::of(1, 2, 3)
        , $add
        , 6
      ]

      , 'add ArrayObject[1,2,3]' => [
        new \ArrayIterator([1, 2, 3])
        , $add
        , 6
      ]
    ];
  }
}
