<?php
namespace tests\PHPixme;

use PHPixme as P;

class mapTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\map'
      , P\map
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\map)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    P\map(function () use ($arrayLike, $expVal, $expKey) {
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

  public function test_return($array = [1, 2, 3])
  {

    $this->assertInstanceOf(
      Closure
      , P\map(P\I)
      , 'map when partially applied should return a closure'
    );
    $result = P\map(P\I)->__invoke($array);
    $this->assertEquals(
      $array
      , $result
      , 'applied with idiot should produce a functionally identical array'
    );
    $result[0] += 1;
    $this->assertNotEquals(
      $array
      , $result
      , 'applied with idiot should not actually be the same instance of array'
    );
  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\map(P\I, $test);
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
      , P\map($hof, $arrayLike)
      , 'map on array like should have the expected resultant'
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
    $x2 = function ($value) {
      return $value * 2;
    };
    return [
      '[1,2] * 2' => [
        [1, 2]
        , $x2
        , [2, 4]
      ]
      , 'ArrayObject[1,2] * 2' => [
        new \ArrayObject([1, 2])
        , $x2
        , [2, 4]
      ]
      , 'ArrayIterator[1,2] * 2' => [
        new \ArrayIterator([1, 2])
        , $x2
        , [2, 4]
      ]
      , 'S[1,2] * 2' => [
        P\Seq::of(1, 2)
        , $x2
        , P\Seq::of(2, 4)
      ]
      , 'Some(1) *2' => [
        P\Some(1)
        , $x2
        , P\Some(2)
      ]
      , 'None * 2' => [
        P\None()
        , $x2
        , P\None()
      ]
      , '[1,2,3] to string' => [
        [1, 2, 3]
        , function ($value, $key) {
          return "$key => $value";
        }
        , ['0 => 1', '1 => 2', '2 => 3']
      ]
    ];
  }
}
