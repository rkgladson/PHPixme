<?php
namespace tests\PHPixme;

use PHPixme as P;

class foldRightTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\foldRight'
      , P\foldRight
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\foldRight)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    $startVal = 1;
    P\foldRight(
      function () use ($startVal, $arrayLike, $expVal, $expKey) {
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
        if (is_object($arrayLike)) {
          $this->assertTrue(
            $arrayLike === func_get_arg(3)
            , 'callback $container should be the same instance as the object'
          );
        } else {
          $this->assertEquals(
            $arrayLike
            , func_get_arg(3)
            , 'callback $container should equal to the array'
          );
        }

        return func_get_arg(0);
      }
      , $startVal
      , $arrayLike
    );
  }

  public function test_return($value = 1, $array = ['a', 'b', 'c', 'd'])
  {
    $this->assertInstanceOf(
      Closure
      , P\foldRight(P\I, $value)
      , 'when partially applied should return a closure'
    );
    $this->assertEquals(
      $value
      , P\foldRight(P\I, $value)->__invoke($array)
      , 'An idiot applied should always return the end value'
    );
    $this->assertEquals(
      $array[0]
      , P\foldRight(P\flip(P\I), $value, $array)
      , 'The flipped idiot applied should always return the first unless empty'
    );
  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\foldRight(P\I, 0, $test);
    $this->assertTrue(
      $prevKey === $test->key()
      , 'The function must not alter the state of an iterator.'
    );
  }

  /**
   * @dataProvider orderProvider
   */
  public function test_order($source, $expected)
  {
    $concat = function ($x, $y) {
      return $x . $y;
    };
    $this->assertEquals(
      $expected
      , P\foldRight($concat, '', $source)
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $startVal, $action, $expected)
  {
    $this->assertEquals(
      $expected
      , P\foldRight($action, $startVal, $arrayLike)
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

  public function orderProvider()
  {
    $source = [1, 2, 3];
    $expected = '321';
    return [
      '[1,2,3]' => [$source, $expected]
      , 'ArrayObject([1,2,3])' => [new \ArrayObject($source), $expected]
      , 'ArrayIterator([1,2,3])' => [new \ArrayIterator($source), $expected]
      , 'Seq(1,2,3)' => [P\Seq($source), $expected]
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
        new \ArrayIterator([1, 2, 3])
        , 0
        , $add
        , 6
      ]
    ];
  }


}
