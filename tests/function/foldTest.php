<?php
namespace tests\PHPixme;

use PHPixme as P;

class foldTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\fold'
      , P\fold
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\fold)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($value, $expVal, $expKey)
  {
    $startVal = 1;
    P\fold(function () use ($startVal, $value, $expVal, $expKey) {
      $this->assertEquals(
        4
        , func_num_args()
        , 'callback should receive four arguments'
      );
      $this->assertEquals(
        $startVal
        , func_get_arg(0)
        , 'callback $prevVal should equal startValue'
      );
      $this->assertEquals(
        $expVal
        , func_get_arg(1)
        , 'callback $value should equal to expected value'
      );
      $this->assertEquals(
        $expKey
        , func_get_arg(2)
        , 'callback $key should equal to expected key'
      );
      if (is_object($value)) {
        $this->assertTrue(
          $value === func_get_arg(3)
          , 'callback $container should be the same instance as the object'
        );
      } else {
        $this->assertEquals(
          $value
          , func_get_arg(3)
          , 'callback $container should equal to the array'
        );
      }

      return func_get_arg(0);
    }, $startVal, $value);
  }

  public function test_return($value = 1, $array = [1, 2, 3, 4])
  {
    $this->assertInstanceOf(
      Closure
      , P\fold(P\I, $value)
      , 'when partially applied should return a closure'
    );
    $this->assertEquals(
      $value
      , P\fold(P\I, $value)->__invoke($array)
      , 'An idiot applied should always return the start value'
    );
    $this->assertEquals(
      $array[count($array) - 1]
      , P\fold(P\flip(P\I), $value, $array)
      , 'The flipped idiot applied should always return the last unless empty'
    );
  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\fold(P\I, 0, $test);
    $this->assertTrue(
      $prevKey === $test->key()
      , 'The function must not alter the state of an iterator.'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $startVal, $action, $expected)
  {
    $this->assertEquals(
      $expected
      , P\fold($action, $startVal, $arrayLike)
    );
  }

  public function callbackProvider()
  {
    return [
      'array callback' => [
        [1], 1, 0
      ]
      , 'iterator aggregate callback' => [
        new \ArrayObject([1]), 1, 0
      ]
      , 'iterator callback' => [
        new \ArrayIterator([1]), 1, 0
      ]
      , 'natural interface callback' => [
        P\Seq([1]), 1, 0
      ]
    ];
  }

  public function scenarioProvider()
  {
    $add = function ($a, $b) {
      return $a + $b;
    };
    return [
      'add simple empty array' => [
        []
        , 0
        , $add
        , 0
      ]
      , 'add simple S[]' => [
        P\Seq::of()
        , 0
        , $add
        , 0
      ]
      , 'add simple None' => [
        P\None()
        , 0
        , $add
        , 0
      ]
      , 'ArrayObject[]' => [
        new \ArrayObject([])
        , 0
        , $add
        , 0
      ]
      , 'ArrayIterator[]' => [
        new \ArrayIterator([])
        , 0
        , $add
        , 0
      ]
      , 'add 1+2+3' => [
        [1, 2, 3]
        , 0
        , $add
        , 6
      ]
      , 'add S[1,2,3]' => [
        P\Seq::of(1, 2, 3)
        , 0
        , $add
        , 6
      ]
      , 'Some(2)+2' => [
        P\Some(2)
        , 2
        , $add
        , 4
      ]
      , 'add ArrayObject[1,2,3]' => [
        new \ArrayObject([1, 2, 3])
        , 0
        , $add
        , 6
      ]
      , 'add ArrayIterator[1,2,3]' => [
        new \ArrayIterator([1, 2, 3])
        , 0
        , $add
        , 6
      ]
    ];
  }
}
