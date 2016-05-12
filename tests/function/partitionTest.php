<?php
namespace tests\PHPixme;

use PHPixme as P;

class partitionTest extends \PHPUnit_Framework_TestCase
{

  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\partition'
      , P\partition
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\partition)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    P\partition(function () use ($arrayLike, $expVal, $expKey) {
      $this->assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $this->assertEquals(
        $expVal
        , func_get_arg(0)
        , 'callback $value should be equal to the value expected'
      );
      $this->assertEquals(
        $expKey
        , func_get_arg(1)
        , 'callback $key should be defined'
      );

      $this->assertTrue(
        $arrayLike === func_get_arg(2)
        , 'callback $container should be the same instance as the source data'
      );
    }, $arrayLike);
  }

  public function test_return(
    $array = [["one" => 1], ["one" => 2], ["two" => 2]]
    , $expected = ["false" => [["one" => 1], ["one" => 2]], "true" => [["two" => 2]]]
  )
  {
    $partitionKeys = P\partition(function ($value) {
      return array_keys($value)[0] === 'two';
    });
    $this->assertInstanceOf(
      Closure
      , $partitionKeys
      , 'when partially applied should return a closure'
    );
    $this->assertEquals($expected, $partitionKeys($array));
  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\partition(P\I, $test);
    $this->assertTrue(
      $prevKey === $test->key()
      , 'The function must not alter the state of an iterator.'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $hof, $expected)
  {
    $this->assertEquals($expected
      , P\partition($hof, $arrayLike)
      , 'should have the expected resultant'
    );
  }

  public function callbackProvider()
  {
    return [
      '[1]' => [
        [1], 1, 0
      ]
      , 'S[1]' => [
        P\Seq::of(1), 1, 0
      ]
      , 'Some(1)' => [
        P\Some::of(1), 1, 0
      ]
      , 'ArrayObject[1]' => [
        new \ArrayObject([1]), 1, 0
      ]
      , 'ArrayIterator[1]' => [
        new \ArrayIterator([1]), 1, 0
      ]
    ];
  }

  public function scenarioProvider()
  {
    //$arrayLike, $hof, $expected
    $m2 = function ($value) {
      return ($value % 2) === 0;
    };
    return [
      '[1,2,3] % 2' => [
        [1, 2, 3]
        , $m2
        , ["false" => [1, 3], "true" => [2]]
      ]
      , 'ArrayObject[1,2,3] % 2' => [
        new \ArrayObject([1, 2, 3])
        , $m2
        , ["false" => [1, 3], "true" => [2]]
      ]
      , 'ArrayIterator[1,2,3] % 2' => [
        new \ArrayIterator([1, 2, 3])
        , $m2
        , ["false" => [1, 3], "true" => [2]]
      ]
      , 'Some(1) % 2' => [
        P\Some(1)
        , $m2
        , ["false" => [1], "true" => []]
      ]
      , 'None % 2' => [
        P\None()
        , $m2
        , ["false" => [], "true" => []]
      ]
      , 'S[1,2,3] % 2' => [
        P\Seq::of(1, 2, 3)
        , $m2
        , P\Seq::from(["false" => P\Seq::of(1, 3), "true" => P\Seq::of(2)])
      ]
    ];
  }
}
