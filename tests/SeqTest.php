<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Seq as testSubject;
use function PHPixme\Seq as testNew;
use const PHPixme\Seq as testConst;

class SeqTest extends \PHPUnit_Framework_TestCase
{
  public function test_Seq_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_seq_companion($value)
  {
    $results = testNew($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }
  /**
   * @dataProvider seqSourceProvider
   */
  public function test_applicative($value)
  {
    $expected = is_array($value) ? array_values($value): iterator_to_array($value, false);

    $results = testSubject::of(...$value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals((new testSubject($expected)), $results);
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_from($value)
  {
    $results = testSubject::from($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  public function test_constructor_iterator_immutability()
  {
    $value = new \ArrayIterator([1, 2, 3, 4, 5]);
    $value->next();
    $prevKey = $value->key();

    new testSubject($value);

    self::assertSame($prevKey, $value->key());
  }

  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
  }

  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject([]))->__construct([1]);
  }

  /**
   * @dataProvider arrayOfThingsProvider
   */
  public function test_toArray($value, $accessor = null)
  {
    self::assertEquals(
      is_null($accessor) ? $value : $value->{$accessor}()
      , testNew($value)->toArray()
      , 'Seq->toArray will should return its inner array, and should be functionally equivalent to the array it was given'
    );
  }

  /**
   * @dataProvider arrayOfThingsProvider
   */
  public function test_values($source, $accessor = null)
  {
    $values = testNew($source)->values();
    self::assertInstanceOf(testSubject::class, $values);
    self::assertEquals(
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
    $expected = array_keys(self::getArray($source, $accessor));
    $keys = testNew($source)->keys();

    self::assertInstanceOf(testSubject::class, $keys);
    self::assertEquals($expected, $keys->toArray());
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_magic_invoke($value)
  {
    $subject = testNew($value);

    foreach ($value as $k => $v) {
      self::assertSame($subject($k), $v);
    }
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_map_callback($value)
  {
    $subject = testNew($value);
    $subject->map(function () use ($subject) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertEquals($subject, $t);
    });
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_map_return($value)
  {
    $subject = testNew($value);

    $result = $subject->map(identity);

    self::assertNotSame($subject, $result);
    self::assertEquals($result, $subject);
  }

  /**
   * @dataProvider seqSourceProvider
   * @requires test_magic_invoke
   */
  public function test_filter_callback($value)
  {
    $seq = testNew($value);
    $seq->filter(function () use ($seq) {
      self::assertTrue(
        3 === func_num_args()
        , 'Seq->filter callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($seq($key)) === $value
        , 'Seq->filter callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'Seq->filter callback $key should be defined'
      );
      self::assertTrue(
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
    $seq = testNew($value);
    $tResult = $seq->filter(function () {
      return true;
    });
    self::assertFalse(
      $tResult === $seq
      , 'Seq->filter callback true is not an identity'
    );
    self::assertEquals(
      $seq
      , $tResult
      , 'Seq->filter callback true still contains the same data'
    );

    $fResult = $seq->filter(function () {
      return false;
    });
    self::assertEquals(
      testNew([])
      , $fResult
      , 'Seq-filter callback false should contain no data'
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
      , 'S[]' => [testNew([])]
      , 'S[1,2,3]' => [testNew([1, 2, 3])]
    ];
  }

  /**
   * @dataProvider seqSourceProvider
   * @requires test_magic_invoke
   */
  function test_filterNot_callback($value)
  {
    $seq = testNew($value);
    $seq->filter(function () use ($seq) {
      self::assertTrue(
        3 === func_num_args()
        , 'Seq->filterNot callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($seq($key)) === $value
        , 'Seq->filterNot callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'Seq->filterNot callback $key should be defined'
      );
      self::assertTrue(
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
    $seq = testNew($value);
    $tResult = $seq->filterNot(function () {
      return false;
    });
    self::assertFalse(
      $tResult === $seq
      , 'Seq->filterNot callback false is not an identity'
    );
    self::assertEquals(
      $seq
      , $tResult
      , 'Seq->filterNot callback false still contains the same data'
    );

    $fResult = $seq->filterNot(function () {
      return true;
    });
    self::assertEquals(
      testNew([])
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
        testSubject::of(testSubject::of(1, 2, 3), testSubject::of(4, 5, 6))
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
    $seq = testNew($value);
    $seq->flatMap(function () use ($seq) {
      self::assertTrue(
        3 === func_num_args()
        , 'Seq->flatMap callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($seq($key)) === $value
        , 'Seq->flatMap callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'Seq->flatMap callback $key should be defined'
      );
      self::assertTrue(
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
    testSubject::of(1, 2, 3)->flatMap(function () {
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
    self::assertEquals(
      $expected
      , testSubject::from($input)->flatMap($id)->toArray()
      , 'Seq->flatMap applied with id should be functionally equivalent its merged array'
    );
  }

  /**
   * @dataProvider nestedTestProvider
   * @depends      test_toArray
   */
  public function test_flatten($input, $expected)
  {
    self::assertEquals(
      $expected
      , testSubject::from($input)->flatten()->toArray()
      , 'Seq->flatten should return a sequence that is functionally equivalent to a merged array'
    );
  }

  /**
   * Ensure the function throws an exception when the contract of a non-traversable item is tried to be merged
   * @expectedException \UnexpectedValueException
   */
  public function test_flatten_contract_broken()
  {
    testSubject::of(1, 2, 3)->flatten();
  }

  /**
   * @dataProvider seqSourceProvider
   */
  public function test_fold_callback($value)
  {
    $seq = testNew($value);
    $seq->fold(function () use ($seq) {
      self::assertTrue(
        4 === func_num_args()
        , 'Seq->fold callback should receive four arguments'
      );

      $prevValue = func_get_arg(0);
      $value = func_get_arg(1);
      $key = func_get_arg(2);
      $container = func_get_arg(3);

      self::assertTrue(
        $prevValue === 0
        , 'Seq->fold callback $prevValue should be its start value'
      );
      self::assertTrue(
        ($seq($key)) === $value
        , 'Seq->fold callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'Seq->fold callback $key should be defined'
      );
      self::assertTrue(
        $seq === $container
        , 'Seq->fold callback $container should be itself'
      );
      return $prevValue;
    }, 0);
  }

  public function foldAdditionProvider()
  {
    return [
      'empty' => [testSubject::from([]), 0]
      , 'from 1 to 9' => [testSubject::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
    ];
  }

  /**
   * @dataProvider foldAdditionProvider
   */
  public function test_fold_scenario_addition(P\Seq $seq, $expected)
  {
    self::assertEquals(
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
    $seq = testNew($value);
    $seq->foldRight(function () use ($seq) {
      self::assertTrue(
        4 === func_num_args()
        , 'Seq->fold callback should receive four arguments'
      );

      $prevValue = func_get_arg(0);
      $value = func_get_arg(1);
      $key = func_get_arg(2);
      $container = func_get_arg(3);

      self::assertTrue(
        $prevValue === 0
        , 'Seq->fold callback $prevValue should be its start value'
      );
      self::assertTrue(
        ($seq($key)) === $value
        , 'Seq->fold callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'Seq->fold callback $key should be defined'
      );
      self::assertTrue(
        $seq === $container
        , 'Seq->fold callback $container should be itself'
      );
      return $prevValue;
    }, 0);
  }

  public function test_foldRight_direction($value = ['e' => 1, 'd' => 2, 'c' => 3, 'b' => 4, 'a' => 5], $expected = 'abcde')
  {
    self::assertTrue(
      $expected === testNew($value)->foldRight(function ($acc, $value, $key) {
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
    self::assertEquals(
      $expected
      , $seq->foldRight(function ($a, $b) {
      return $a + $b;
    }, 0)
      , 'Seq->fold applied to addition should produce the sum of the sequence'
    );
  }




  /**
   * @dataProvider forAllProvider
   * @requires test_magic_invoke
   */
  public function test_forAll_callback(P\Seq $subject)
  {
    $ran = 0;

    $subject->forAll(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k)||is_string($k));
      self::assertSame($subject,$t);

      $ran += 1;
      return true;
    });
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider forAllProvider
   */
  public function test_forAll_scenario_positive(P\Seq $seq, $expected)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    self::assertEquals(
      $expected
      , $seq->forAll($positive)
      , 'Seq->forAll callback should all be as expected based on positive result'
    );
  }

  /**
   * @dataProvider forNoneProvider
   */
  public function test_forNone_callback(P\Seq $subject)
  {
    $ran = 0;

    $subject->forNone(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k)||is_string($k));
      self::assertSame($subject,$t);

      $ran += 1;
      return true;
    });

    if ($subject->isEmpty()) {
      self::assertEquals(0, $ran);
    } else {
      self::assertGreaterThan(0, $ran);
    }
  }

  /**
   * @dataProvider forNoneProvider
   */
  public function test_forNone_scenario_positive(P\Seq $seq, $expected)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    self::assertEquals(
      $expected
      , $seq->forNone($positive)
      , 'Seq->forNone callback should have none be as expected based on positive result'
    );
  }

  /**
   * @dataProvider forSomeProvider
   */
  public function test_forSome_callback(P\Seq $subject)
  {
    $ran = 0;

    $subject->forSome(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k)||is_string($k));
      self::assertSame($subject,$t);

      $ran += 1;
      return true;
    });

    if ($subject->isEmpty()) {
      self::assertEquals(0, $ran);
    } else {
      self::assertGreaterThan(0, $ran);
    }
  }

  /**
   * @dataProvider forSomeProvider
   */
  public function test_forSome_scenario_positive(P\Seq $subject, $expected)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    self::assertEquals(
      $expected
      , $subject->forSome($positive)
      , 'Seq->forNone callback should at least one be as expected based on positive result'
    );
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduce_callback(P\Seq $subject)
  {
    $ran = 1; // Start off with 1 already consumed
    $head = $subject->head();

    $subject->reduce(function () use ($subject, $head, &$ran) {
      self::assertEquals(4, func_num_args());
      list($a, $v, $k, $t) = func_get_args();

      self::assertSame($head, $a);
      self::assertSame($subject->offsetGet($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return $a;
    });
    self::assertEquals(count($subject), $ran);
  }

  public function test_reduce_contract_broken()
  {
    $this->expectException(\LengthException::class);
    testSubject::of()->reduce(noop);
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduce_scenario_add(P\Seq $subject, $expected)
  {
    $add2 = function ($a, $b) {
      return $a + $b;
    };
    self::assertEquals($expected, $subject->reduce($add2));
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduceRight_callback(P\Seq $subject)
  {
    $ran = 1; // there should be one less iteration than length
    $head = $subject->reverse()->head();

    $subject->reduceRight(function () use ($subject, $head, &$ran) {
      self::assertEquals(4, func_num_args());
      list($a, $v, $k, $t) = func_get_args();

      self::assertSame($head, $a);
      self::assertSame($subject->offsetGet($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return $a;
    });
    self::assertEquals(count($subject), $ran);
  }

  public function test_reduceRight_contract_broken()
  {
    $this->expectException(\LengthException::class);
    testSubject::of()->reduceRight(noop);
  }

  public function test_reduceRight_direction(
    $value = ['e' => 1, 'd' => 2, 'c' => 3, 'b' => 4, 5]
    , $expected = '5bcde'
  )
  {
    $joinKeys = function ($acc, $value, $key) {
      return $acc . $key;
    };
    self::assertEquals($expected, testNew($value)->reduceRight($joinKeys));
  }

  /**
   * @dataProvider reduceAdditionProvider
   */
  public function test_reduceRight_scenario_add(P\Seq $subject, $expected)
  {
    $add2 = function ($a, $b) {
      return $a + $b;
    };

    self::assertEquals($expected, $subject->reduceRight($add2));
  }

  /**
   * @dataProvider unionDataProvider
   */
  public function test_union(P\Seq $seq, $arrayLikeN, $expected)
  {
    self::assertEquals(
      $expected
      , call_user_func_array([$seq, 'union'], $arrayLikeN)
      , 'Seq->union is expected to join the data with itself and the passed array likes'
    );
  }


  public function test_find_callback()
  {
    $ran = 0;
    $subject = testSubject::of(1, 2, 3);

    $subject->find(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return true; // Returning true forces a return of the first looked at
    });
    self::assertEquals(1, $ran);
  }


  public function test_find()
  {
    $subject = testSubject::of(1, 2, 3);

    $found = $subject->find(bTrue);
    $missing = $subject->find(bFalse);

    self::assertInstanceOf(P\Some::class, $found);
    self::assertSame($subject->head(), $found->get());
    self::assertInstanceOf(P\None::class, $missing);
  }

  /**
   * @dataProvider walkProvider
   * @param $subject \PHPixme\Seq
   * @param int $length
   */
  public function test_walk_callback(P\Seq $subject, $length)
  {
    $ran = 0;

    $subject->walk(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
    });
    self::assertEquals($length, $ran);
  }

  public function reduceAdditionProvider()
  {
    return [
      'only zero' => [testSubject::of(0), 0]
      , 'from 1 to 9' => [testSubject::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
    ];
  }

  /**
   * @dataProvider walkProvider
   * @param $subject \PHPixme\Seq
   */
  public function test_walk(P\Seq $subject)
  {
    self::assertSame($subject, $subject->walk(noop));
  }


  /**
   * @dataProvider headProvider
   */
  public function test_head(P\Seq $subject, $expects)
  {
    self::assertEquals($expects, $subject->head());
  }

  /**
   * @dataProvider headMaybeProvider
   */
  public function test_headMaybe(P\Seq $subject, $expects)
  {
    $result = $subject->headMaybe();

    self::assertInstanceOf(P\Maybe::class, $result);
    self::assertEquals($expects, $result);
  }

  /**
   * @dataProvider tailProvider
   */
  public function test_tail(P\Seq $seq, $expects)
  {
    self::assertEquals($expects, $seq->tail());
  }

  /**
   * @dataProvider indexOfProvider
   */
  public function test_indexOf(P\Seq $haystack, $needle, $expected)
  {
    self::assertEquals(
      $expected
      , $haystack->indexOf($needle)
      , 'should yield the expected results for $needle in $haystack'
    );
  }


  /**
   * @dataProvider partitionProvider
   */
  public function test_partition_callback(P\Seq $subject)
  {
    $ran = 0;

    $subject->partition(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return true;
    });
    self::assertEquals(count($subject), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider partitionProvider
   */
  public function test_partition(P\Seq $seq, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->partition($hof)
      , 'Seq->partition should separate as expected the results of the $hof based on its Seq("false"=>Seq(value),"true"=>Seq(value)) value'
    );
  }

  public function partitionProvider()
  {
    return [
      'from 1 to 9 partitioned by odd (true) and even(false)' => [
        testSubject::of(1, 2, 3, 4, 5, 6, 7, 8, 9)
        , function ($value, $key) {
          return ($key % 2) === 0;
        }
        , testSubject::from([
          "false" => testSubject::of(2, 4, 6, 8)
          , "true" => testSubject::of(1, 3, 5, 7, 9)
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
      self::assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      self::assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    self::assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider partitionWithKeyProvider
   */
  public function test_partitionWithKey(P\Seq $seq, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->partitionWithKey($hof)
      , 'should separate as expected the results of the $hof based on its Seq("false"=>Seq([key, value]),"true"=>Seq([key, value])) value'
    );
  }

  public function partitionWithKeyProvider()
  {
    return [
      'from 1 to 9 partitioned by odd (true) and even(false)' => [
        testSubject::of(1, 2, 3, 4, 5, 6, 7, 8, 9)
        , function ($value, $key) {
          return ($key % 2) === 0;
        }
        , testSubject::from([
          "false" => testSubject::from([[1, 2], [3, 4], [5, 6], [7, 8]])
          , "true" => testSubject::from([[0, 1], [2, 3], [4, 5], [6, 7], [8, 9]])
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
      self::assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      self::assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    self::assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider groupProvider
   */
  public function test_group(P\Seq $seq, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->group($hof)
      , 'applied to the key values given by the hof, should be a nested sequence of expected Seq'
    );
  }


  /**
   * @dataProvider groupWithKeyProvider
   */
  public function test_groupWithKey_callback(P\Seq $seq)
  {
    $ran = 0;
    $seq->groupWithKey(function () use ($seq, &$ran) {
      self::assertTrue(
        3 === func_num_args()
        , 'callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($seq($key)) === $value
        , 'callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'callback $key should be defined'
      );
      self::assertTrue(
        $seq === $container
        , 'callback $container should be itself'
      );
      $ran += 1;
      return true;
    });
    self::assertEquals(count($seq), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider groupWithKeyProvider
   */
  public function test_groupWithKey(P\Seq $seq, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->groupWithKey($hof)
      , 'applied to the key values given by the hof, should be a nested sequence of expected Seq'
    );
  }

  public function groupWithKeyProvider()
  {
    return [
      '' => [
        testSubject::of(1, '2', 3, P\Some(4), 5, '6', 7)
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
        , testSubject::from([
          'number' => testSubject::from([[0, 1], [2, 3], [4, 5], [6, 7]])
          , 'string' => testSubject::from([[1, '2'], [5, 6]])
          , 'object' => testSubject::from([[3, P\Some(4)]])
        ])
      ]
    ];
  }

  public function dropProvider()
  {
    return [
      'empty drop 5' => [
        testSubject::of()
        , 5
        , testSubject::of()
      ]
      , 'S[1,2,3,4] drop 3' => [
        testSubject::of(1, 2, 3, 4)
        , 3
        , testSubject::from([3 => 4])
      ]
    ];
  }

  /**
   * @dataProvider dropProvider
   */
  public function test_drop(P\Seq $seq, $number, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->drop($number)
      , 'Seq->drop of amount results are functionally equivilent to expected'
    );
  }


  /**
   * @dataProvider dropRightProvider
   */
  public function test_dropRight($seq, $amount, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->dropRight($amount)
      , 'Seq->dropRight of amount should produce the Sequence expected'
    );
  }

  public function takeProvider()
  {
    return [
      'S[]->takeRight(5)' => [
        testSubject::of(), 5, testSubject::of()
      ]
      , 'S[1,2,3,4,5,6]->takeRight(2)' => [
        testSubject::of(1, 2, 3, 4, 5, 6), 2, testSubject::from([0 => 1, 1 => 2])
      ]
    ];
  }

  /**
   * @dataProvider takeProvider
   */
  public function test_take($seq, $amount, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->take($amount)
      , 'Seq->take of amount should yield expected'
    );
  }

  public function takeRightProvider()
  {
    return [
      'S[]->takeRight(5)' => [
        testSubject::of(), 5, testSubject::of()
      ]
      , 'S[1,2,3,4,5,6]->takeRight(2)' => [
        testSubject::of(1, 2, 3, 4, 5, 6), 2, testSubject::from([4 => 5, 5 => 6])
      ]
    ];
  }

  /**
   * @dataProvider takeRightProvider
   */
  public function test_takeRight(P\Seq $seq, $amount, $expected)
  {
    self::assertEquals(
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
    self::assertEquals(
      empty($source)
      , testSubject::from($source)->isEmpty()
      , 'Seq->isEmpty should be true if the source was empty'
    );
  }

  /**
   * @dataProvider justArraysProvider
   */
  public function test_count($source)
  {
    self::assertEquals(
      count($source)
      , testSubject::from($source)->count()
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
    self::assertEquals(
      implode($glue, $array)
      , testSubject::from($array)->toString($glue)
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
    self::assertEquals(
      json_encode($array)
      , testSubject::from($array)->toJson()
      , 'Seq->toJson should be functionally equivalent to json_encode(Seq->toArray)'
    );
  }

  public function reverseProvider()
  {
    return [
      'S[1,2,3]' => [
        testSubject::of(1, 2, 3)
        , testSubject::from([2 => 3, 1 => 2, 0 => 1])
      ]
    ];
  }

  /**
   * @dataProvider reverseProvider
   */
  public function test_reverse(P\Seq $seq, $expected)
  {
    self::assertEquals(
      $expected
      , $seq->reverse()
      , 'Seq->reverse should reverse the traversal order of a Seq'
    );
  }


  /**
   * @dataProvider forEachProvider
   */
  public function test_forEach($seq, $keyR, $valueR)
  {
    $idx = 0;
    $count = 0;
    foreach ($seq as $key => $value) {
      self::assertSame($keyR[$idx], $key);
      self::assertSame($valueR[$idx], $value);
      $idx += 1;
      foreach ($seq as $k => $v) {
        $count += 1;
      }
    }
    self::assertEquals(count($seq), $idx);
    self::assertEquals(count($seq) ** 2, $count);
  }

  public function test_offsetExists($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $eq = testSubject::from($source);
    self::assertTrue($eq->offsetExists($offset));
    self::assertFalse($eq->offsetExists($notOffset));
  }

  public function test_offsetGet($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $eq = testSubject::from($source);
    self::assertTrue($value === $eq->offsetGet($offset));
    self::assertNull($eq->offsetGet($notOffset));
  }

  public function test_offsetGetMaybe($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $eq = testSubject::from($source);
    $maybeOffset = $eq->offsetGetMaybe($offset);
    self::assertInstanceOf(P\Some::class, $maybeOffset);
    self::assertTrue($value === $maybeOffset->getOrElse(!$value));
    self::assertInstanceOf(P\None::class, $eq->offsetGetMaybe($notOffset));
  }

  public function test_offsetGetAttempt($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $eq = testSubject::from($source);
    $attemptOffset = $eq->offsetGetAttempt($offset);
    self::assertInstanceOf(P\Success::class, $attemptOffset);
    self::assertTrue($value === $attemptOffset->getOrElse(!$value));
    $notOffset = $offset . 'nope';
    $attemptOffset = $eq->offsetGetAttempt($notOffset);
    self::assertInstanceOf(P\Failure::class, $attemptOffset);
    $exception = $attemptOffset->failed()->get();
    self::assertInstanceOf(P\exception\VacuousOffsetException::class, $exception);
    self::assertEquals($notOffset, $exception->get());
  }

  public function test_offsetSet($value = true)
  {
    $emptySeq = testSubject::from([]);
    $expectedInsertedAtLocation[1] = $value;
    $insertedAtLocation = $emptySeq->offsetSet(1, $value);
    self::assertInstanceOf(testSubject::class, $insertedAtLocation);
    self::assertEquals($expectedInsertedAtLocation, $insertedAtLocation->toArray());
    $expectedInsertedAtEnd[] = $value;
    $insertedAtEnd = $emptySeq->offsetSet(null, $value);
    self::assertEquals($expectedInsertedAtEnd, $insertedAtEnd->toArray());
  }

  public function test_offsetApply()
  {
    $ident = testSubject::of(1, 2, 3);
    $seq_1_4_3 = $ident->offsetApply(1, P\add(2));
    self::assertInstanceOf(testSubject::class, $seq_1_4_3);
    self::assertTrue($ident !== $seq_1_4_3);
    self::assertEquals([1, 4, 3], $seq_1_4_3->toArray());
    self::assertNotEquals($ident->toArray(), $ident->offsetApply(1, P\add(2))->toArray());
    self::assertEquals([1, 4, 3], $ident->offsetApply(1, P\mul(2))->toArray());
    self::assertTrue(
      $ident === $ident->offsetApply(null, function () {
        throw new \Exception('Should never run!');
      })
      , 'Should be an identity when no offset exists'
    );
    $ran = 0;
    $offset = 0;
    $ident->offsetApply($offset, function () use (&$ran, $ident, $offset) {
      self::assertEquals(
        1
        , func_num_args()
        , 'callback should receive only one argument'
      );
      self::assertTrue(
        func_get_arg(0) === $ident->offsetGet($offset)
        , 'The value should be the contents at that offset'
      );
      $ran += 1;
      return $offset;
    });
    self::assertEquals(1, $ran, 'callback should run when the offset does exist');
  }

  public function test_offsetUnset($source = [1, 2, 3], $offset = 2)
  {
    $expected = $source;
    unset($expected[$offset]);
    self::assertEquals($expected, testNew($source)->offsetUnset($offset)->toArray());
  }

  public function test_toArrayObject($source = [1, 2, 3])
  {
    $arrayObject = testSubject::from($source)->toArrayAccess();
    self::assertInstanceOf(\ArrayObject::class, $arrayObject);
    self::assertEquals($source, $arrayObject->getArrayCopy());
  }


  private static function getArray($source, $accessor = null)
  {
    return is_null($accessor) ? $source : $source->{$accessor}();
  }

  public function arrayOfThingsProvider()
  {
    return [
      [[]]
      , [[1, 2, 3]]
      , [['one' => 1, 'two' => 2]]
      , [[P\Some(1), P\None()]]
      , [[testSubject::of(1, 2, 3), testSubject::of(4, 5, 6)]]
      , [new \ArrayObject(['one' => 1, 'two' => 2]), 'getArrayCopy']
      , [new \ArrayIterator(['one' => 1, 'two' => 2]), 'getArrayCopy']
    ];
  }

  public function dropRightProvider()
  {
    return [
      'empty drop right 5' => [
        testSubject::of()
        , 5
        , testSubject::of()
      ]
      , 'S[1,2,3,4] drop right 3' => [
        testSubject::of(1, 2, 3, 4)
        , 3
        , testSubject::from([0 => 1])
      ]
    ];
  }


  public function forAllProvider()
  {
    return [
      'seq from 1 to 4' => [testSubject::of(1, 2, 3, 4), true]
      , 'seq from -2 to 2' => [testSubject::of(-2, -1, 0, 1, 2), false]
      , 'seq from -4 to -1' => [testSubject::of(-4, -3, -2, -1), false]
    ];
  }

  public function forNoneProvider()
  {
    return [
      'seq from 1 to 4' => [testSubject::of(1, 2, 3, 4), false]
      , 'seq from -2 to 2' => [testSubject::of(-2, -1, 0, 1, 2), false]
      , 'seq from -4 to -1' => [testSubject::of(-4, -3, -2, -1), true]
    ];
  }

  public function forSomeProvider()
  {
    return [
      'seq from 1 to 4' => [testSubject::of(1, 2, 3, 4), true]
      , 'seq from -2 to 2' => [testSubject::of(-2, -1, 0, 1, 2), true]
      , 'seq from -4 to -1' => [testSubject::of(-4, -3, -2, -1), false]
    ];
  }

  public function unionDataProvider()
  {
    return [
      'S[] with Some(1) and []' => [
        testSubject::of()
        , [[], P\Some::of(1)]
        , testSubject::of(1)]
      , 'S[1,2,3] with [4], S[5,6], and None' => [
        testSubject::of(1, 2, 3)
        , [[4], testSubject::of(5, 6), P\None()]
        , testSubject::of(1, 2, 3, 4, 5, 6)
      ]
      , 'S[None, Some(1)] with Some(1)' => [
        testSubject::of(P\None, P\Some(1))
        , [P\None(), P\Some(2)]
        , testSubject::of(P\None, P\Some(1), 2)
      ]
    ];
  }

  public function groupProvider()
  {
    return [
      '' => [
        testSubject::of(1, '2', 3, P\Some(4), 5, '6', 7)
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
        , testSubject::from([
          'number' => testSubject::of(1, 3, 5, 7)
          , 'string' => testSubject::of('2', 6)
          , 'object' => testSubject::of(P\Some(4))
        ])
      ]
    ];
  }

  public function headProvider()
  {
    return [
      'keyless' => [
        testSubject::of(1, 2, 3)
        , 1
      ]
      , 'keyed' => [
        testSubject::from([
          'one' => 1
          , 'two' => 2
          , 'three' => 3
        ])
        , 1
      ]
      , 'empty' => [
        testSubject::of()
        , null
      ]
    ];
  }

  public function headMaybeProvider()
  {
    return [
      'keyless' => [
        testSubject::of(1, 2, 3)
        , P\Some(1)
      ]
      , 'keyed' => [
        testSubject::from([
          'one' => 1
          , 'two' => 2
          , 'three' => 3
        ])
        , P\Some(1)
      ]
      , 'some null head ' => [
        testSubject::of(null)
        , P\Some(null)
      ]
      , 'empty' => [
        testSubject::of()
        , P\None()
      ]
    ];
  }

  public function tailProvider()
  {
    return [
      'keyless' => [
        testSubject::of(1, 2, 3)
        , testSubject::of(2, 3)
      ]
      , 'keyed' => [
        testSubject::from([
          'one' => 1
          , 'two' => 2
          , 'three' => 3
        ])
        , testSubject::from([
          'two' => 2
          , 'three' => 3
        ])
      ]
      , 'empty' => [
        testSubject::of()
        , testSubject::of()
      ]
    ];
  }


  public function indexOfProvider()
  {
    $none = P\None();
    $some1 = P\Some(1);
    $one = 1;
    return [
      'keyed source find None S[one=>1, none=>None, some=>Some(1) ]' => [
        testSubject::from(['one' => $one, 'none' => $none, 'some' => $some1])
        , $none
        , P\Some('none')
      ]
      , 'source find None S[1,None, Some(1)]' => [
        testSubject::of($one, $none, $some1)
        , $none
        , P\Some(1)
      ]
      , 'source find Some(1) in S[1,2,Some(1),3]' => [
        testSubject::of(1, 2, $some1, 3)
        , $some1
        , P\Some(2)
      ]
      , 'find null in 0 index' => [
        testSubject::from([null])
        , null
        , P\Some(0)
      ]
      , 'fail to find Some(1) in S[1,2,3]' => [
        testSubject::of(1, 2, 3)
        , $some1
        , $none
      ]
      , 'fail to find Some(1) in S[]' => [
        testSubject::of()
        , $some1
        , $none
      ]
    ];
  }

  public function walkProvider()
  {
    return [
      'from 1 to 9' => [
        testSubject::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 9
      ]
      , 'Nothing' => [
        testSubject::of(), 0
      ]
    ];
  }

  public function forEachProvider()
  {
    $some1 = P\Some(1);
    $some2 =  P\Some(2);
    return [
      'S[1,2,3,4]' => [
        testSubject::of(1, 2, 3, 4)
        , [0, 1, 2, 3]
        , [1, 2, 3, 4]
      ]
      , 'S[1,2,3,4]->reverse()' => [
        testSubject::of(1, 2, 3, 4)->reverse()
        , [3, 2, 1, 0]
        , [4, 3, 2, 1]
      ]
      , 'S[Some(1),Some(2)]' => [
        testSubject::of($some1, $some2)
        , [0, 1]
        , [$some1, $some2]
      ]
    ];
  }

}


