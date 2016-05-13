<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/11/2016
 * Time: 3:38 PM
 */

namespace tests\PHPixme;

use PHPixme as P;

class SeqTest extends \PHPUnit_Framework_TestCase
{
  public function test_Seq_constants()
  {
    $this->assertTrue(
      P\Seq::class === P\Seq
      , 'The constant for the Class and Function should be equal to the Class Path'
    );
    $this->assertTrue(
      function_exists(P\Seq)
      , 'The companion function exists for the class.'
    );
  }

  public function seqSourceProvider()
  {
    // TODO: Figure out how to add a generator to this without it expiring after the first test.
    return [
      '[]' => [[]]
      , '[1,2,3]' => [[1, 2, 3]]
      , 'Some(1)' => [P\Some(1)]
      , 'None' => [P\None()]
      , 'Array({one:1, two: 2})' => [['one' => 1, 'two' => 2]]
      , 'ArrayObject({one:1, two: 2})' => [new \ArrayObject(['one' => 1, 'two' => 2])]
      , 'ArrayIterator({one:1, two: 2})' => [new \ArrayIterator(['one' => 1, 'two' => 2])]
      , 'JustAIterator({one:1, two: 2})' => [new JustAIterator(['one' => 1, 'two' => 2])]
      , 'S[]' => [P\Seq([])]
      , 'S[1,2,3]' => [P\Seq([1, 2, 3])]
    ];
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_seq_companion($value)
  {
    $seq = P\Seq($value);
    $this->assertStringEndsWith(
      '\Seq'
      , P\Seq
      , 'Ensure the constant ends with the function/class name'
    );
    $this->assertInstanceOf(
      P\Seq
      , $seq
      , 'Seq companion function should produce instances of Seq class'
    );
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_seq_static_of($value)
  {

    $seq = call_user_func_array(P\Seq . '::of', is_array($value) ? $value : [$value]);
    $this->assertInstanceOf(
      P\Seq
      , $seq
      , 'Seq::of should produce a instance of Seq class'
    );
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_static_from($value)
  {
    $seq = P\Seq::from($value);
    $this->assertInstanceOf(
      P\Seq
      , $seq
      , 'Seq::from should produce an instance of Seq Class'
    );
  }

  public function test_constructor_iterator_immutability()
  {
    $testIter = new \ArrayIterator([1, 2, 3, 4, 5]);
    $testIter->next();
    $prevKey = $testIter->key();
    new P\Seq($testIter);
    $this->assertTrue(
      $prevKey === $testIter->key()
      , 'the constructor aught not to ever change the state of an iterator'
    );

  }

  public function arrayOfThingsProvider()
  {
    return [
      [[]]
      , [[1, 2, 3]]
      , [['one' => 1, 'two' => 2]]
      , [[P\Some(1), P\None()]]
      , [[P\Seq::of(1, 2, 3), P\Seq::of(4, 5, 6)]]
      , [new \ArrayObject(['one' => 1, 'two' => 2]), 'getArrayCopy']
      , [new \ArrayIterator(['one' => 1, 'two' => 2]), 'getArrayCopy']
    ];
  }

  /**
   * @dataProvider arrayOfThingsProvider
   */
  public function test_toArray($value, $accessor = null)
  {
    $this->assertEquals(
      is_null($accessor) ? $value : $value->{$accessor}()
      , P\Seq($value)->toArray()
      , 'Seq->toArray will should return its inner array, and should be functionally equivalent to the array it was given'
    );
  }

  /**
   * @dataProvider arrayOfThingsProvider
   */
  public function test_values($source, $accessor = null)
  {
    $values = P\Seq($source)->values();
    $this->assertInstanceOf(
      P\Seq
      , $values
      , 'Seq->values should return an instance of itself'
    );
    $this->assertEquals(
      array_values(is_null($accessor) ? $source : $source->{$accessor}())
      , $values->toArray()
      , 'Seq->values should return a sequence only containing the values'
    );
  }

  /**
   * @dataProvider arrayOfThingsProvider
   */
  public function test_keys($source, $accessor = null)
  {
    $keys = P\Seq($source)->keys();
    $this->assertInstanceOf(
      P\Seq
      , $keys
      , 'Seq->keys should return an instance of itself'
    );
    $this->assertEquals(
      array_keys(is_null($accessor) ? $source : $source->{$accessor}())
      , $keys->toArray()
      , 'Seq->keys should return a sequence only containing the keys'
    );
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_magic_invoke($value)
  {
    $seq = P\Seq($value);

    foreach ($value as $k => $v) {
      $this->assertTrue(
        $seq($k) === $v
        , 'Seq->__invoke should accept a key and return its value at the key'
      );
    }
  }

  /**
   * @dataProvider seqSourceProvider
   * @requires test_magic_invoke
   */
  public function test_map_callback($value)
  {
    $seq = P\Seq($value);
    $seq->map(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->map callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->map callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->map callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->map callback $container should be itself'
      );
    });
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_map_scenario_identity($value)
  {
    $id = function ($x) {
      return $x;
    };
    $seq = P\Seq($value);
    $result = $seq->map($id);
    $this->assertFalse(
      $seq === $result
      , 'Seq->map should not return the same instance'
    );
    $this->assertEquals(
      $result
      , $seq
      , 'Seq->map applied with id should be functionally equivalent'
    );
  }


  /**
   * @dataProvider seqSourceProvider
   * @requires test_magic_invoke
   */
  public function test_filter_callback($value)
  {
    $seq = P\Seq($value);
    $seq->filter(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->filter callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->filter callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->filter callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->filter callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_filter($value)
  {
    $seq = P\Seq($value);
    $tResult = $seq->filter(function () {
      return true;
    });
    $this->assertFalse(
      $tResult === $seq
      , 'Seq->filter callback true is not an identity'
    );
    $this->assertEquals(
      $seq
      , $tResult
      , 'Seq->filter callback true still contains the same data'
    );

    $fResult = $seq->filter(function () {
      return false;
    });
    $this->assertEquals(
      P\Seq([])
      , $fResult
      , 'Seq-filter callback false should contain no data'
    );
  }

  /**
   * @dataProvider seqSourceProvider
   * @requires test_magic_invoke
   */
  function test_filterNot_callback($value)
  {
    $seq = P\Seq($value);
    $seq->filter(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->filterNot callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->filterNot callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->filterNot callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->filterNot callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_filterNot($value)
  {
    $seq = P\Seq($value);
    $tResult = $seq->filterNot(function () {
      return false;
    });
    $this->assertFalse(
      $tResult === $seq
      , 'Seq->filterNot callback false is not an identity'
    );
    $this->assertEquals(
      $seq
      , $tResult
      , 'Seq->filterNot callback false still contains the same data'
    );

    $fResult = $seq->filterNot(function () {
      return true;
    });
    $this->assertEquals(
      P\Seq([])
      , $fResult
      , 'Seq-filterNot callback true should contain no data'
    );
  }

  public function nestedTestProvider()
  {
    // Provides flatten operations with the solution
    return [
      'nested array' => [
        [[1, 2, 3], [4, 5, 6]]
        , [1, 2, 3, 4, 5, 6]
      ]
      , 'array with some' => [
        [P\Some(1), P\Some(2), P\Some(3)]
        , [1, 2, 3]
      ]
      , 'Seq of Seq' => [
        P\Seq::of(P\Seq::of(1, 2, 3), P\Seq::of(4, 5, 6))
        , [1, 2, 3, 4, 5, 6]
      ]
      , 'Seq of array' => [
        P\seq::of([1, 2, 3], [4, 5, 6])
        , [1, 2, 3, 4, 5, 6]
      ]
    ];
  }


  /**
   * @dataProvider nestedTestProvider
   */
  public function test_flatMap_callback($value)
  {
    $seq = P\Seq($value);
    $seq->flatMap(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->flatMap callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->flatMap callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->flatMap callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->flatMap callback $container should be itself'
      );
      return $value;
    });
  }

  /**
   * Ensure the function throws an exception when the contract of a non-traversable item is passed to it from the $hof
   * @expectedException \UnexpectedValueException
   */
  public function test_flatMap_contract_broken()
  {
    P\Seq::of(1, 2, 3)->flatMap(function () {
      return true;
    });
  }

  /**
   * @dataProvider nestedTestProvider
   * @depends      test_toArray
   */
  public function test_flatMap_scenario_idenity($input, $expected)
  {
    $id = function ($value) {
      return $value;
    };
    $this->assertEquals(
      $expected
      , P\Seq::from($input)->flatMap($id)->toArray()
      , 'Seq->flatMap applied with id should be functionally equivalent its merged array'
    );
  }

  /**
   * @dataProvider nestedTestProvider
   * @depends      test_toArray
   */
  public function test_flatten($input, $expected)
  {
    $this->assertEquals(
      $expected
      , P\Seq::from($input)->flatten()->toArray()
      , 'Seq->flatten should return a sequence that is functionally equivalent to a merged array'
    );
  }

  /**
   * Ensure the function throws an exception when the contract of a non-traversable item is tried to be merged
   * @expectedException \UnexpectedValueException
   */
  public function test_flatten_contract_broken()
  {
    P\Seq::of(1, 2, 3)->flatten();
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_fold_callback($value)
  {
    $seq = P\Seq($value);
    $seq->fold(function () use ($seq) {
      $this->assertTrue(
        4 === func_num_args()
        , 'Seq->fold callback should receive four arguments'
      );

      $prevValue = func_get_arg(0);
      $value = func_get_arg(1);
      $key = func_get_arg(2);
      $container = func_get_arg(3);

      $this->assertTrue(
        $prevValue === 0
        , 'Seq->fold callback $prevValue should be its start value'
      );
      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->fold callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->fold callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->fold callback $container should be itself'
      );
      return $prevValue;
    }, 0);
  }

  public function foldAdditionProvider()
  {
    return [
      'empty' => [P\Seq::from([]), 0]
      , 'from 1 to 9' => [P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
    ];
  }

  /**
   * @dataProvider foldAdditionProvider
   */
  public function test_fold_scenario_addition(P\Seq $seq, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->fold(function ($a, $b) {
      return $a + $b;
    }, 0)
      , 'Seq->fold applied to addition should produce the sum of the sequence'
    );
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_foldRight_callback($value)
  {
    $seq = P\Seq($value);
    $seq->foldRight(function () use ($seq) {
      $this->assertTrue(
        4 === func_num_args()
        , 'Seq->fold callback should receive four arguments'
      );

      $prevValue = func_get_arg(0);
      $value = func_get_arg(1);
      $key = func_get_arg(2);
      $container = func_get_arg(3);

      $this->assertTrue(
        $prevValue === 0
        , 'Seq->fold callback $prevValue should be its start value'
      );
      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->fold callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->fold callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->fold callback $container should be itself'
      );
      return $prevValue;
    }, 0);
  }

  public function test_foldRight_direction($value = ['e' => 1, 'd' => 2, 'c' => 3, 'b' => 4, 'a' => 5], $expected = 'abcde')
  {
    $this->assertTrue(
      $expected === P\Seq($value)->foldRight(function ($acc, $value, $key) {
        return $acc . $key;
      }, '')
      , 'The traversal of the Seq should be the reverse of the internal order'
    );
  }

  /**
   * @dataProvider foldAdditionProvider
   */
  public function test_foldRight_scenario_addition(P\Seq $seq, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->foldRight(function ($a, $b) {
      return $a + $b;
    }, 0)
      , 'Seq->fold applied to addition should produce the sum of the sequence'
    );
  }


  public function forAllProvider()
  {
    return [
      'seq from 1 to 4' => [P\Seq::of(1, 2, 3, 4), true]
      , 'seq from -2 to 2' => [P\Seq::of(-2, -1, 0, 1, 2), false]
      , 'seq from -4 to -1' => [P\Seq::of(-4, -3, -2, -1), false]
    ];
  }

  /**
   * @dataProvider forAllProvider
   * @requires test_magic_invoke
   */
  public function test_forAll_callback(P\Seq $seq)
  {
    $seq->forAll(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->forAll callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->forAll callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->forAll callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->forAll callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @dataProvider forAllProvider
   */
  public function test_forAll_scenario_positive(P\Seq $seq, $expected)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    $this->assertEquals(
      $expected
      , $seq->forAll($positive)
      , 'Seq->forAll callback should all be as expected based on positive result'
    );
  }

  public function forNoneProvider()
  {
    return [
      'seq from 1 to 4' => [P\Seq::of(1, 2, 3, 4), false]
      , 'seq from -2 to 2' => [P\Seq::of(-2, -1, 0, 1, 2), false]
      , 'seq from -4 to -1' => [P\Seq::of(-4, -3, -2, -1), true]
    ];
  }

  /**
   * @dataProvider forNoneProvider
   */
  public function test_forNone_callback(P\Seq $seq)
  {
    $seq->forNone(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->forNone callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->forNone callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->forNone callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->forNone callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @dataProvider forNoneProvider
   */
  public function test_forNone_scenario_positive(P\Seq $seq, $expected)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    $this->assertEquals(
      $expected
      , $seq->forNone($positive)
      , 'Seq->forNone callback should have none be as expected based on positive result'
    );
  }

  public function forSomeProvider()
  {
    return [
      'seq from 1 to 4' => [P\Seq::of(1, 2, 3, 4), true]
      , 'seq from -2 to 2' => [P\Seq::of(-2, -1, 0, 1, 2), true]
      , 'seq from -4 to -1' => [P\Seq::of(-4, -3, -2, -1), false]
    ];
  }

  /**
   * @dataProvider forSomeProvider
   */
  public function test_forSome_callback(P\Seq $seq)
  {
    $seq->forSome(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->forSome callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->forSome callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->forSome callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->forSome callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @dataProvider forSomeProvider
   */
  public function test_forSome_scenario_positive(P\Seq $seq, $expected)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    $this->assertEquals(
      $expected
      , $seq->forSome($positive)
      , 'Seq->forNone callback should at least one be as expected based on positive result'
    );
  }

  public function reduceAdditionProvider()
  {
    return [
      'only zero' => [P\Seq::of(0), 0]
      , 'from 1 to 9' => [P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
    ];
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduce_callback(P\Seq $seq)
  {
    $head = $seq->head();
    $seq->reduce(function () use ($seq, $head) {
      $this->assertTrue(
        4 === func_num_args()
        , 'Seq->reduce callback should receive four arguments'
      );

      $prevValue = func_get_arg(0);
      $value = func_get_arg(1);
      $key = func_get_arg(2);
      $container = func_get_arg(3);

      $this->assertTrue(
        $prevValue === $head
        , 'Seq->reduce callback $prevValue should be the first value in the Seq'
      );
      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->reduce callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->reduce callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->reduce callback $container should be itself'
      );
      return $prevValue;
    });
  }

  /**
   * Ensure that the contract is maintained that reduce on none is undefined behavior
   * @expectedException \LengthException
   */
  public function test_reduce_contract_broken()
  {
    P\Seq::of()->reduce(function () {
      return true;
    });
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduce_scenario_add(P\Seq $seq, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->reduce(function ($a, $b) {
      return $a + $b;
    })
      , 'Seq->reduce application of add should produced the expected result'
    );
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduceRight_callback(P\Seq $seq)
  {
    $head = $seq->reverse()->head();
    $seq->reduceRight(function () use ($seq, $head) {
      $this->assertTrue(
        4 === func_num_args()
        , 'Seq->reduceRight callback should receive four arguments'
      );

      $prevValue = func_get_arg(0);
      $value = func_get_arg(1);
      $key = func_get_arg(2);
      $container = func_get_arg(3);

      $this->assertTrue(
        $prevValue === $head
        , 'Seq->reduceRight callback $prevValue should be the first value in the Seq'
      );
      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->reduceRight callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->reduceRight callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->reduceRight callback $container should be itself'
      );
      return $prevValue;
    });
  }

  /**
   * Ensure that the contract is maintained that reduce on none is undefined behavior
   * @expectedException \LengthException
   */
  public function test_reduceRight_contract_broken()
  {
    P\Seq::of()->reduceRight(function () {
      return true;
    });
  }

  public function test_reduceRight_direction($value = ['e' => 1, 'd' => 2, 'c' => 3, 'b' => 4, 5], $expected = '5bcde')
  {
    $joinKeys = function ($acc, $value, $key) {
      return $acc . $key;
    };
    $this->assertEquals(
      $expected
      , P\Seq($value)->reduceRight($joinKeys)
      , 'The traversal of the Seq should be the reverse of the internal order'
    );
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduceRight_scenario_add(P\Seq $seq, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->reduceRight(function ($a, $b) {
      return $a + $b;
    })
      , 'Seq->reduceRight application of add should produced the expected result'
    );
  }

  public function unionDataProvider()
  {
    return [
      'S[] with Some(1) and []' => [
        P\Seq::of()
        , [[], P\Some::of(1)]
        , P\Seq::of(1)]
      , 'S[1,2,3] with [4], S[5,6], and None' => [
        P\Seq::of(1, 2, 3)
        , [[4], P\Seq::of(5, 6), P\None()]
        , P\Seq::of(1, 2, 3, 4, 5, 6)
      ]
      , 'S[None, Some(1)] with Some(1)' => [
        P\Seq::of(P\None, P\Some(1))
        , [P\None(), P\Some(2)]
        , P\Seq::of(P\None, P\Some(1), 2)
      ]
    ];

  }

  /**
   * @dataProvider unionDataProvider
   */
  public function test_union(P\Seq $seq, $arrayLikeN, $expected)
  {
    $this->assertEquals(
      $expected
      , call_user_func_array([$seq, 'union'], $arrayLikeN)
      , 'Seq->union is expected to join the data with itself and the passed array likes'
    );
  }

  public function findProvider()
  {
    return [
      'find 1' => [
        P\Seq::of(1, 2, 3)
        , 1
        , P\Some(1)
      ]
      , 'fail to find 4' => [
        P\Seq::of(1, 2, 3)
        , 4
        , P\None()
      ]
    ];
  }

  /**
   * @dataProvider findProvider
   */
  public function test_find_callback(P\Seq $seq)
  {
    $seq->find(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->find callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->find callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->find callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->find callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @dataProvider findProvider
   */
  public function test_find(P\Seq $seq, $value, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->find(function ($x) use ($value) {
      return $x === $value;
    })
      , 'Seq->find should result in the expected value for any positive otucome of callback'
    );
  }

  public function walkProvider()
  {
    return [
      'from 1 to 9' => [
        P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 9
      ]
      , 'Nothing' => [
        P\Seq::of(), 0
      ]
    ];
  }

  /**
   * @dataProvider walkProvider
   * @param $seq \PHPixme\Seq
   */
  public function test_walk_callback(P\Seq $seq)
  {
    $seq->walk(function () use ($seq) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Seq->walk callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'Seq->walk callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->walk callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'Seq->walk callback $container should be itself'
      );
    });
  }

  /**
   * @dataProvider walkProvider
   * @param $seq \PHPixme\Seq
   * @param $length int
   */
  public function test_walk(P\Seq $seq, $length)
  {
    $ran = 0;
    $this->assertTrue(
      $seq === $seq->walk(function () use (&$ran) {
        $ran += 1;
      })
      , 'Seq-> walk should return its own instance'
    );
    $this->assertTrue(
      $length === $ran
      , 'Seq->walk should of ran the length of the sequence'
    );
  }


  public function headProvider()
  {
    return [
      'keyless' => [
        P\Seq::of(1, 2, 3)
        , 1
      ]
      , 'keyed' => [
        P\Seq::from([
          'one' => 1
          , 'two' => 2
          , 'three' => 3
        ])
        , 1
      ]
      , 'empty' => [
        P\Seq::of()
        , null
      ]
    ];
  }

  /**
   * @dataProvider headProvider
   */
  public function test_head(P\Seq $seq, $expects)
  {
    $this->assertEquals(
      $expects
      , $seq->head()
      , 'Seq->head should return the head element'
    );
  }


  public function tailProvider()
  {
    return [
      'keyless' => [
        P\Seq::of(1, 2, 3)
        , P\Seq::of(2, 3)
      ]
      , 'keyed' => [
        P\Seq::from([
          'one' => 1
          , 'two' => 2
          , 'three' => 3
        ])
        , P\Seq::from([
          'two' => 2
          , 'three' => 3
        ])
      ]
      , 'empty' => [
        P\Seq::of()
        , P\Seq::of()
      ]
    ];
  }

  /**
   * @dataProvider tailProvider
   */
  public function test_tail(P\Seq $seq, $expects)
  {
    $this->assertEquals(
      $expects
      , $seq->tail()
      , 'Seq->head should return the rest of the Sequence'
    );
  }

  public function indexOfProvider()
  {
    $none = P\None;
    $some1 = P\Some(1);
    $one = 1;
    return [
      'keyed source find None S[one=>1, none=>None, some=>Some(1) ]' => [
        P\Seq::from(['one' => $one, 'none' => $none, 'some' => $some1])
        , $none
        , 'none'
      ]
      , 'source find None S[1,None, Some(1)]' => [
        P\Seq::of($one, $none, $some1)
        , $none
        , 1
      ]
      , 'source find Some(1) in S[1,2,Some(1),3]' => [
        P\Seq::of(1, 2, $some1, 3)
        , $some1
        , 2
      ]
      , 'fail to find Some(1) in S[1,2,3]' => [
        P\Seq::of(1, 2, 3)
        , $some1
        , -1
      ]
      , 'fail to find Some(1) in S[]' => [
        P\Seq::of()
        , $some1
        , -1
      ]
    ];
  }

  /**
   * @dataProvider indexOfProvider
   */
  public function test_indexOf(P\Seq $haystack, $needle, $expected)
  {
    $this->assertEquals(
      $expected
      , $haystack->indexOf($needle)
      , 'should yield the expected results for $needle in $haystack'
    );
  }


  /**
   * @dataProvider partitionProvider
   */
  public function test_partition_callback(P\Seq $seq)
  {
    $ran = 0;
    $seq->partition(function () use ($seq, &$ran) {
      $this->assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    $this->assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider partitionProvider
   */
  public function test_partition(P\Seq $seq, $hof, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->partition($hof)
      , 'Seq->partition should separate as expected the results of the $hof based on its Seq("false"=>Seq(value),"true"=>Seq(value)) value'
    );
  }

  public function partitionProvider()
  {
    return [
      'from 1 to 9 partitioned by odd (true) and even(false)' => [
        P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9)
        , function ($value, $key) {
          return ($key % 2) === 0;
        }
        , P\Seq::from([
          "false" => P\Seq::of(2, 4, 6, 8)
          , "true" => P\Seq::of(1, 3, 5, 7, 9)
        ])
      ]
    ];
  }


  /**
   * @dataProvider partitionWithKeyProvider
   */
  public function test_partitionWithKey_callback(P\Seq $seq)
  {
    $ran = 0;
    $seq->partitionWithKey(function () use ($seq, &$ran) {
      $this->assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    $this->assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider partitionWithKeyProvider
   */
  public function test_partitionWithKey(P\Seq $seq, $hof, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->partitionWithKey($hof)
      , 'should separate as expected the results of the $hof based on its Seq("false"=>Seq([key, value]),"true"=>Seq([key, value])) value'
    );
  }

  public function partitionWithKeyProvider()
  {
    return [
      'from 1 to 9 partitioned by odd (true) and even(false)' => [
        P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9)
        , function ($value, $key) {
          return ($key % 2) === 0;
        }
        , P\Seq::from([
          "false" => P\Seq::from([[1, 2], [3, 4], [5, 6], [7, 8]])
          , "true" => P\Seq::from([[0, 1], [2, 3], [4, 5], [6, 7], [8, 9]])
        ])
      ]
    ];
  }

  /**
   * @dataProvider groupProvider
   */
  public function test_group_callback(P\Seq $seq)
  {
    $ran = 0;
    $seq->group(function () use ($seq, &$ran) {
      $this->assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    $this->assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider groupProvider
   */
  public function test_group(P\Seq $seq, $hof, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->group($hof)
      , 'applied to the key values given by the hof, should be a nested sequence of expected Seq'
    );
  }

  public function groupProvider()
  {
    return [
      '' => [
        P\Seq::of(1, '2', 3, P\Some(4), 5, '6', 7)
        , function ($value) {
          if (is_string($value)) {
            return 'string';
          }
          if (is_numeric($value)) {
            return 'number';
          }
          if (is_object($value)) {
            return 'object';
          }
          return 'donno';
        }
        , P\Seq::from([
          'number' => P\Seq::of(1, 3, 5, 7)
          , 'string' => P\Seq::of('2', 6)
          , 'object' => P\Seq::of(P\Some(4))
        ])
      ]
    ];
  }

  /**
   * @dataProvider groupWithKeyProvider
   */
  public function test_groupWithKey_callback(P\Seq $seq)
  {
    $ran = 0;
    $seq->groupWithKey(function () use ($seq, &$ran) {
      $this->assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    $this->assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider groupWithKeyProvider
   */
  public function test_groupWithKey(P\Seq $seq, $hof, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->groupWithKey($hof)
      , 'applied to the key values given by the hof, should be a nested sequence of expected Seq'
    );
  }

  public function groupWithKeyProvider()
  {
    return [
      '' => [
        P\Seq::of(1, '2', 3, P\Some(4), 5, '6', 7)
        , function ($value) {
          if (is_string($value)) {
            return 'string';
          }
          if (is_numeric($value)) {
            return 'number';
          }
          if (is_object($value)) {
            return 'object';
          }
          return 'donno';
        }
        , P\Seq::from([
          'number' => P\Seq::from([[0, 1], [2, 3], [4, 5], [6, 7]])
          , 'string' => P\Seq::from([[1, '2'], [5, 6]])
          , 'object' => P\Seq::from([[3, P\Some(4)]])
        ])
      ]
    ];
  }

  public function dropProvider()
  {
    return [
      'empty drop 5' => [
        P\Seq::of()
        , 5
        , P\Seq::of()
      ]
      , 'S[1,2,3,4] drop 3' => [
        P\Seq::of(1, 2, 3, 4)
        , 3
        , P\Seq::from([3 => 4])
      ]
    ];
  }

  /**
   * @dataProvider dropProvider
   */
  public function test_drop(P\Seq $seq, $number, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->drop($number)
      , 'Seq->drop of amount results are functionally equivilent to expected'
    );
  }

  public function dropRightProvider()
  {
    return [
      'empty drop right 5' => [
        P\Seq::of()
        , 5
        , P\Seq::of()
      ]
      , 'S[1,2,3,4] drop right 3' => [
        P\Seq::of(1, 2, 3, 4)
        , 3
        , P\Seq::from([0 => 1])
      ]
    ];
  }

  /**
   * @dataProvider dropRightProvider
   */
  public function test_dropRight($seq, $amount, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->dropRight($amount)
      , 'Seq->dropRight of amount should produce the Sequence expected'
    );
  }

  public function takeProvider()
  {
    return [
      'S[]->takeRight(5)' => [
        P\Seq::of(), 5, P\Seq::of()
      ]
      , 'S[1,2,3,4,5,6]->takeRight(2)' => [
        P\Seq::of(1, 2, 3, 4, 5, 6), 2, P\Seq::from([0 => 1, 1 => 2])
      ]
    ];
  }

  /**
   * @dataProvider takeProvider
   */
  public function test_take($seq, $amount, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->take($amount)
      , 'Seq->take of amount should yield expected'
    );
  }

  public function takeRightProvider()
  {
    return [
      'S[]->takeRight(5)' => [
        P\Seq::of(), 5, P\Seq::of()
      ]
      , 'S[1,2,3,4,5,6]->takeRight(2)' => [
        P\Seq::of(1, 2, 3, 4, 5, 6), 2, P\Seq::from([4 => 5, 5 => 6])
      ]
    ];
  }

  /**
   * @dataProvider takeRightProvider
   */
  public function test_takeRight(P\Seq $seq, $amount, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->takeRight($amount)
      , 'Seq->takeRight of amount should yield expected'
    );
  }

  public function justArraysProvider()
  {
    return [
      '[]' => [
        []
      ],
      '[1, 2, 3, ... 9]' => [
        [1, 2, 3, 4, 5, 6, 7, 8, 9]
      ]
    ];
  }

  /**
   * @dataProvider justArraysProvider
   */
  public function test_isEmpty($source)
  {
    $this->assertEquals(
      empty($source)
      , P\Seq::from($source)->isEmpty()
      , 'Seq->isEmpty should be true if the source was empty'
    );
  }

  /**
   * @dataProvider justArraysProvider
   */
  public function test_count($source)
  {
    $this->assertEquals(
      count($source)
      , P\Seq::from($source)->count()
      , 'Seq->count should be the amount of items that was sent to it'
    );
  }


  public function toStringProvider()
  {
    return [
      '[]' => [
        [], ''
      ]
      , 'S[integer]' => [
        [1, 2, 3, 4, 5]
        , '!'
      ]
      , 'S[string]' => [
        ['a', 'b', 'c', 'd']
        , ';'
      ]
      , 'S[string => integer]' => [
        ['one' => 1, 'two' => 2]
        , ', '
      ]
    ];
  }

  /**
   * @dataProvider toStringProvider
   */
  public function test_toString($array, $glue)
  {
    $this->assertEquals(
      implode($glue, $array)
      , P\Seq::from($array)->toString($glue)
    );
  }

  public function toJsonProvider()
  {
    return [
      'empty' => [
        []
      ]
      , 'S{integer}' => [
        [1, 2, 3, 4, 5]
      ]
      , 'S{string}' => [
        ['a', 'b', 'c', 'd']
      ]
      , 'Keyed S{integer}' => [
        ['one' => 1, 'two' => 2]
      ]
    ];
  }

  /**
   * @dataProvider toJsonProvider
   */
  public function test_toJson($array)
  {
    $this->assertEquals(
      json_encode($array)
      , P\Seq::from($array)->toJson()
      , 'Seq->toJson should be functionally equivalent to json_encode(Seq->toArray)'
    );
  }

  public function reverseProvider()
  {
    return [
      'S[1,2,3]' => [
        P\Seq::of(1, 2, 3)
        , P\Seq::from([2 => 3, 1 => 2, 0 => 1])
      ]
    ];
  }

  /**
   * @dataProvider reverseProvider
   */
  public function test_reverse(P\Seq $seq, $expected)
  {
    $this->assertEquals(
      $expected
      , $seq->reverse()
      , 'Seq->reverse should reverse the traversal order of a Seq'
    );
  }

  public function forEachProvider()
  {
    return [
      'S[1,2,3,4]' => [
        P\Seq::of(1, 2, 3, 4)
        , [0, 1, 2, 3]
        , [1, 2, 3, 4]
      ]
      , 'S[1,2,3,4]->reverse()' => [
        P\Seq::of(1, 2, 3, 4)->reverse()
        , [3, 2, 1, 0]
        , [4, 3, 2, 1]
      ]
      , 'S[Some(1),Some(2)]' => [
        P\Seq::of(P\Some(1), P\Some(2))
        , [0, 1]
        , [P\Some(1), P\Some(2)]
      ]
    ];
  }

  /**
   * @dataProvider forEachProvider
   */
  public function test_forEach($seq, $keyR, $valueR)
  {
    $idx = 0;
    $count = 0;
    foreach ($seq as $key => $value) {
      $this->assertEquals(
        $keyR[$idx]
        , $key
        , 'The key at this step should equal the expected key'
      );
      $this->assertEquals(
        $valueR[$idx]
        , $value
        , 'The value at this step should equal the expected value'
      );
      $idx += 1;

      foreach ($seq as $k => $v) {
        $count += 1;
      }
    }
    $this->assertEquals(
      count($seq)
      , $idx
      , 'The foreach should of ran the length of the contained array'
    );
    $this->assertEquals(
      count($seq) ** 2
      , $count
      , 'The foreach should not cause a mutation on iteration when nested'
    );
  }

  public function test_offsetExists($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $eq = P\Seq::from($source);
    $this->assertTrue($eq->offsetExists($offset));
    $this->assertFalse($eq->offsetExists($notOffset));
  }

  public function test_offsetGet($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $eq = P\Seq::from($source);
    $this->assertTrue($value === $eq->offsetGet($offset));
    $this->assertNull($eq->offsetGet($notOffset));
  }

  public function test_offsetGetMaybe($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $eq = P\Seq::from($source);
    $maybeOffset = $eq->offsetGetMaybe($offset);
    $this->assertInstanceOf(P\Some::class, $maybeOffset);
    $this->assertTrue($value === $maybeOffset->getOrElse(!$value));
    $this->assertInstanceOf(P\None::class, $eq->offsetGetMaybe($notOffset));
  }

  public function test_offsetGetAttempt($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $eq = P\Seq::from($source);
    $attemptOffset = $eq->offsetGetAttempt($offset);
    $this->assertInstanceOf(P\Success::class, $attemptOffset);
    $this->assertTrue($value === $attemptOffset->getOrElse(!$value));
    $notOffset = $offset . 'nope';
    $attemptOffset = $eq->offsetGetAttempt($notOffset);
    $this->assertInstanceOf(P\Failure::class, $attemptOffset);
    $exception = $attemptOffset->failed()->get();
    $this->assertInstanceOf(P\exception\VacuousOffsetException::class, $exception);
    $this->assertEquals($notOffset, $exception->get());
  }

  public function test_offsetSet($value = true)
  {
    $emptySeq = P\Seq::from([]);
    $expectedInsertedAtLocation[1] = $value;
    $insertedAtLocation = $emptySeq->offsetSet(1, $value);
    $this->assertInstanceOf(P\Seq::class, $insertedAtLocation);
    $this->assertEquals($expectedInsertedAtLocation, $insertedAtLocation->toArray());
    $expectedInsertedAtEnd[] = $value;
    $insertedAtEnd = $emptySeq->offsetSet(null, $value);
    $this->assertEquals($expectedInsertedAtEnd, $insertedAtEnd->toArray());
  }

  public function test_offsetApply()
  {
    $ident = P\Seq::of(1, 2, 3);
    $seq_1_4_3 = $ident->offsetApply(1, P\add(2));
    $this->assertInstanceOf(P\Seq::class, $seq_1_4_3);
    $this->assertTrue($ident !== $seq_1_4_3);
    $this->assertEquals([1, 4, 3], $seq_1_4_3->toArray());
    $this->assertNotEquals($ident->toArray(), $ident->offsetApply(1, P\add(2))->toArray());
    $this->assertEquals([1, 4, 3], $ident->offsetApply(1, P\mul(2))->toArray());
    $this->assertTrue(
      $ident === $ident->offsetApply(null, function () {
        throw new \Exception('Should never run!');
      })
      , 'Should be an identity when no offset exists'
    );
    $ran = 0;
    $offset = 0;
    $ident->offsetApply($offset, function () use (&$ran, $ident, $offset) {
      $this->assertEquals(
        1
        , func_num_args()
        , 'callback should receive only one argument'
      );
      $this->assertTrue(
        func_get_arg(0) === $ident->offsetGet($offset)
        , 'The value should be the contents at that offset'
      );
      $ran += 1;
      return $offset;
    });
    $this->assertEquals(1, $ran, 'callback should run when the offset does exist');
  }

  public function test_offsetUnset($source = [1, 2, 3], $offset = 2)
  {
    $expected = $source;
    unset($expected[$offset]);
    $this->assertEquals($expected, P\Seq($source)->offsetUnset($offset)->toArray());
  }

  public function test_toArrayObject($source = [1, 2, 3])
  {
    $arrayObject = P\Seq::from($source)->toArrayObject();
    $this->assertInstanceOf(\ArrayObject::class, $arrayObject);
    $this->assertEquals($source, $arrayObject->getArrayCopy());
  }

}
