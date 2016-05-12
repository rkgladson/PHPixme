<?php
namespace tests\PHPixme;

use PHPixme as P;

class reduceRightTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\reduceRight'
      , P\reduceRight
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\reduceRight)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider undefinedBehaviorProvider
   * @expectedException \LengthException
   */
  public function test_contract_violation($arrayLike = [])
  {
    P\reduceRight(P\I, $arrayLike);
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $lastVal, $expVal, $expKey)
  {
    P\reduceRight(function () use ($lastVal, $expVal, $expKey, $arrayLike) {
      $arity = func_num_args();
      $args = func_get_args();
      $this->assertEquals(
        4
        , $arity
        , 'callback should receive four arguments'
      );
      $this->assertEquals(
        $lastVal
        , $args[0]
        , 'callback $prevVal should equal startValue'
      );
      $this->assertEquals(
        $expVal
        , $args[1]
        , 'callback should equal to expected value'
      );
      $this->assertEquals(
        $expKey
        , $args[2]
        , 'callback $key should equal to expected key'
      );
      if (is_object($arrayLike)) {
        $this->assertTrue(
          $arrayLike === $args[3]
          , 'callback $container should be the same instance as the source data'
        );
      } else {
        $this->assertEquals(
          $arrayLike
          , $args[3]
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
      , P\reduceRight(P\I)
      , 'when partially applied should return a closure'
    );
    $this->assertEquals(
      $array[count($array) - 1]
      , P\reduceRight(P\I)->__invoke($array)
      , 'An idiot applied should always return the last value'
    );
    $this->assertEquals(
      $array[0]
      , P\reduceRight(P\flip(P\I), $array)
      , 'The flipped idiot applied should always return the first value'
    );
  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\reduceRight(P\I, $test);
    $this->assertTrue(
      $prevKey === $test->key()
      , 'The function must not alter the state of an iterator.'
    );
  }

  /**
   * @dataProvider orderProvider
   */
  public function test_order($arrayLike, $expected)
  {
    $concat = function ($acc, $value) {
      return $acc . $value;
    };
    $this->assertEquals(
      $expected
      , P\reduceRight($concat, $arrayLike)
      , 'should traverse the array like in a reverse order.'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $action, $expected)
  {
    $this->assertEquals(
      $expected
      , P\reduceRight($action, $arrayLike)
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
    /*$arrayLike, $firstVal, $expVal, $expKey*/
    return [
      'array callback' => [
        [1, 2], 2, 1, 0
      ]
      , 'iterator aggregate callback' => [
        new \ArrayObject([1, 2]), 2, 1, 0
      ]
      , 'iterator callback' => [
        new \ArrayIterator([1, 2]), 2, 1, 0
      ]
      , 'natural interface callback' => [
        P\Seq::of(1, 2), 2, 1, 0
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
        new \ArrayObject([1])
        , $add
        , 1
      ]
      , 'add ArrayIterator[1]' => [
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
        new \ArrayObject([1, 2, 3])
        , $add
        , 6
      ]
      , 'add ArrayIterator[1,2,3]' => [
        new \ArrayIterator([1, 2, 3])
        , $add
        , 6
      ]
    ];
  }

  public function orderProvider()
  {
    $source = [1, 2, 3];
    $output = '321';
    return [
      '[1,2,3]' => [$source, $output]
      , 'ArrayObject(1,2,3)' => [new \ArrayObject($source), $output]
      , 'ArrayIterator(1,2,3)' => [new \ArrayIterator($source), $output]
      , 'Seq(1,2,3)' => [P\Seq($source), $output]
    ];
  }
}
