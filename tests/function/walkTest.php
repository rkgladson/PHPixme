<?php
namespace tests\PHPixme;

use PHPixme as P;

class walkTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\walk'
      , P\walk
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\walk)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    $ran = 0;
    P\walk(function () use ($arrayLike, $expVal, $expKey, &$ran) {
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
        , 'callback $container should be the same as the source data being operated upon'
      );
      $ran += 1;
    }, $arrayLike);
    $this->assertTrue($ran > 0, 'the callback should of ran');
  }

  public function test_return($array = [1, 2, 3])
  {

    $this->assertInstanceOf(
      Closure
      , P\walk(P\I)
      , 'map when partially applied should return a closure'
    );
    $result = P\walk(P\I)->__invoke($array);
    $this->assertTrue(
      $array === $result
      , 'applied should produce a the same as what was passed into it.'
    );

  }

  public function test_iterator_immutability()
  {
    $test = new \ArrayIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\walk(P\I, $test);
    $this->assertTrue(
      $prevKey === $test->key()
      , 'The function must not alter the state of an iterator.'
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
      , 'ArrayIterator[1]' => [
        new \ArrayIterator([1]), 1, 0
      ]
      , 'ArrayObject[1]' => [
        new \ArrayObject([1]), 1, 0
      ]
    ];
  }
}
