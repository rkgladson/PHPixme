<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Seq as testSubject;
use function PHPixme\Seq as testNew;
use const PHPixme\Seq as testConst;

/**
 * Class SeqTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Seq
 */
class SeqTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_Seq_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  /** @coversNothing  */
  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
  }

  /**
   * @coversNothing
   */
  public function test_constructor_iterator_immutability()
  {
    $value = new \ArrayIterator([1, 2, 3, 4, 5]);
    $value->next();
    $prevKey = $value->key();

    new testSubject($value);

    self::assertSame($prevKey, $value->key());
  }

  /** @coversNothing  */
  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject([]))->__construct([1]);
  }

  /**
   * @covers ::__construct
   * @dataProvider valueProvider
   */
  public function test_new($value) {
    $result = new testSubject($value);
    self::assertAttributeSame(self::toArray($value), 'hash', $result);
  }

  /**
   * @dataProvider valueProvider
   * @covers PHPixme\Seq
   */
  public function test_seq_companion($value)
  {
    $results = testNew($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  /**
   * @dataProvider valueProvider
   * @covers ::of
   */
  public function test_applicative($value)
  {
    // PHP's 'unpack' operator cannot handle keys :(
    $source = array_values(self::toArray($value));

    $results = testSubject::of(...$source);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals((new testSubject($source)), $results);
  }

  /**
   * @dataProvider valueProvider
   * @covers ::from
   */
  public function test_from($value)
  {
    $results = testSubject::from($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  /**
   * @dataProvider valueProvider
   * @covers ::count
   */
  public function test_count($value)
  {
    $expected = count(self::toArray($value));
    $subject = testNew($value);
    self::assertEquals($expected, $subject->count());
    self::assertEquals($expected, count($subject));
  }

  /**
   * @dataProvider valueProvider
   * @covers ::isEmpty
   */
  public function test_isEmpty($value)
  {
    $expected = empty(self::toArray($value));
    $subject = testNew($value);

    self::assertEquals($expected, $subject->isEmpty());
  }

  /**
   * @dataProvider valueProvider
   * @covers ::toArray
   */
  public function test_toArray($value)
  {
    self::assertEquals(self::toArray($value), testNew($value)->toArray());
  }

  /**
   * @dataProvider valueProvider
   * @covers ::values
   */
  public function test_values($value)
  {
    $result = testNew($value)->values();
    self::assertEquals(testNew(array_values(self::toArray($value))), $result);
  }

  /**
   * @dataProvider valueProvider
   * @covers ::keys
   */
  public function test_keys($value)
  {
    self::assertEquals(testNew(array_keys(self::toArray($value))), testNew($value)->keys());
  }


  /**
   * @dataProvider valueProvider
   * @covers ::reverse
   */
  public function test_reverse($value)
  {
    self::assertEquals(testNew(array_reverse(self::toArray($value), true)), testNew($value)->reverse());
  }

  /**
   * @dataProvider valueProvider
   * @covers ::__invoke
   */
  public function test_magic_invoke($value)
  {
    $subject = testNew($value);

    foreach ($value as $k => $v) {
      self::assertSame($subject($k), $v);
    }
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_map_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->map(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertEquals($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::map
   */
  public function test_map_return(testSubject $subject)
  {
    $result = $subject->map(identity);

    self::assertNotSame($subject, $result);
    self::assertEquals($result, $subject);
  }

  /**
   * @covers ::apply
   */
  public function test_apply() {
    $functor = testSubject::of(1,2,3);
    $map = function ($hof) use ($functor) {
      return $functor->map($hof);
    };
    $subject = testSubject::of(identity, noop);

    self::assertEquals($functor::from($subject->flatMap($map)), $subject->apply($functor));
  }

  public function test_apply_contract() {
    $this->expectException(P\exception\InvalidContentException::class);
    testNew([null])->apply(testSubject::of(1,2,3));
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_filter_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->filter(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertEquals($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::filter
   */
  public function test_filter(testSubject $subject)
  {
    $empty = testNew([]);

    $tResult = $subject->filter(bTrue);
    $fResult = $subject->filter(bFalse);
    // True
    self::assertNotSame($subject, $tResult);
    self::assertEquals($subject, $tResult);
    // False
    self::assertNotSame($subject, $fResult);
    self::assertEquals($empty, $fResult);
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  function test_filterNot_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->filterNot(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertEquals($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::filterNot
   */
  public function test_filterNot(testSubject $subject)
  {
    $empty = testNew([]);

    $tResult = $subject->filterNot(bTrue);
    $fResult = $subject->filterNot(bFalse);
    // True
    self::assertNotSame($subject, $tResult);
    self::assertEquals($empty, $tResult);
    // False
    self::assertNotSame($subject, $fResult);
    self::assertEquals($subject, $fResult);
  }

  /**
   * @dataProvider nestedValueProvider
   * @coversNothing
   */
  public function test_flatMap_callback($value)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->flatMap(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertEquals($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testSubject::of(null)->flatMap(noop);
  }

  /**
   * @dataProvider nestedValueProvider
   * @covers ::flatMap
   */
  public function test_flatMap_return($value, $expected)
  {
    $subject = testNew($value);

    $result = testNew($value)->flatMap(identity);

    self::assertNotSame($subject, $result);
    self::assertEquals(testNew($expected), $result);
  }

  /**
   * @dataProvider nestedValueProvider
   * @covers ::flatten
   */
  public function test_flatten_return($value, $expected)
  {
    $subject = testNew($value);

    $result = $subject->flatten();

    self::assertNotSame($subject, $result);
    self::assertEquals(testNew($expected), $result);
  }

  /** @coversNothing  */
  public function test_flatten_contract_broken()
  {
    $this->expectException(P\exception\InvalidContentException::class);
    testSubject::of(null)->flatten();
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_fold_callback(testSubject $subject)
  {
    $ran = 0;
    $startVal = new \stdClass();

    $subject->fold(function () use ($subject, $startVal, &$ran) {
      self::assertEquals(4, func_num_args());
      list($p, $v, $k, $t) = func_get_args();

      self::assertSame($startVal, $p);
      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return $p;
    }, $startVal);
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider numericSubjectProvider
   * @covers ::fold
   */
  public function test_fold_return(testSubject $subject)
  {
    $startValue = 0;
    $add2 = function ($a, $b) {
      return $a + $b;
    };

    self::assertEquals($startValue + array_sum($subject->toArray()), $subject->fold($add2, 0));
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_foldRight_callback(testSubject $subject)
  {
    $ran = 0;
    $startVal = new \stdClass();

    $subject->foldRight(function () use ($subject, $startVal, &$ran) {
      self::assertEquals(4, func_num_args());
      list($p, $v, $k, $t) = func_get_args();

      self::assertSame($startVal, $p);
      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return $p;
    }, $startVal);
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::foldRight
   */
  public function test_foldRight_direction(testSubject $subject)
  {
    $expected = $subject->reverse()->keys()->toArray();
    $assembleKeys = function ($p, $v, $k, $c) {
      $p[] = $k;
      return $p;
    };

    $result = $subject->foldRight($assembleKeys, []);

    self::assertEquals($expected, $result);
  }

  /**
   * @dataProvider numericSubjectProvider
   * @covers ::foldRight
   */
  public function test_foldRight_return(testSubject $subject)
  {
    $startValue = 0;
    $add2 = function ($a, $b) {
      return $a + $b;
    };

    self::assertEquals($startValue + array_sum($subject->reverse()->toArray()), $subject->fold($add2, 0));
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_forAll_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->forAll(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return true;
    });
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider subjectNumericRangeProvider
   * @covers ::forAll
   */
  public function test_forAll_return(testSubject $subject)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    $expected = count(array_filter($subject->toArray(), $positive)) === count($subject);

    self::assertEquals($expected, $subject->forAll($positive));
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_forNone_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->forNone(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k) || is_string($k));
      self::assertSame($subject, $t);

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
   * @dataProvider subjectNumericRangeProvider
   * @covers ::forNone
   */
  public function test_forNone_scenario_positive(testSubject $subject)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    $expected = count(array_filter($subject->toArray(), $positive)) === 0;

    self::assertEquals($expected, $subject->forNone($positive));
  }

  /**
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_forSome_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->forSome(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_integer($k) || is_string($k));
      self::assertSame($subject, $t);

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
   * @dataProvider subjectNumericRangeProvider
   * @covers ::forSome
   */
  public function test_forSome_return(testSubject $subject)
  {
    $positive = function ($value) {
      return $value > 0;
    };
    $expected = count(array_filter($subject->toArray(), $positive)) > 0;

    self::assertEquals($expected, $subject->forSome($positive));
  }

  /**
   * @dataProvider nonEmptySubjectProvider
   * @coversNothing
   */
  public function test_reduce_callback(testSubject $subject)
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

  /** @covers ::reduce  */
  public function test_reduce_contract_broken()
  {
    $this->expectException(\LengthException::class);
    testNew([])->reduce(noop);
  }

  /**
   * @dataProvider numericSubjectProvider
   * @covers ::reduce
   */
  public function test_reduce_return(testSubject $subject)
  {
    if (!$subject->isEmpty()) {
      $add2 = function ($a, $b) {
        return $a + $b;
      };
      self::assertEquals(array_sum($subject->toArray()), $subject->reduce($add2));
    }
  }

  /**
   * @dataProvider nonEmptySubjectProvider
   * @coversNothing
   */
  public function test_reduceRight_callback(testSubject $subject)
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

  /**
   * @coversNothing
   */
  public function test_reduceRight_direction()
  {
    $value = ['e' => 1, 'd' => 2, 'c' => 3, 'b' => 4, 5];
    $expected = '5bcde';
    $joinKeys = function ($acc, $value, $key) {
      return $acc . $key;
    };

    self::assertEquals($expected, testNew($value)->reduceRight($joinKeys));
  }

  /** @covers ::reduceRight  */
  public function test_reduceRight_contract_broken()
  {
    $this->expectException(\LengthException::class);
    testNew([])->reduceRight(noop);
  }

  /**
   * @dataProvider numericSubjectProvider
   * @covers ::reduceRight
   */
  public function test_reduceRight_return(testSubject $subject)
  {
    if (!$subject->isEmpty()) {
      $add2 = function ($a, $b) {
        return $a + $b;
      };
      self::assertEquals(array_sum($subject->reverse()->toArray()), $subject->reduceRight($add2));
    }
  }

  /**
   * @dataProvider unionDataProvider
   * @covers ::union
   */
  public function test_union(testSubject $subject, $arrayLikeN, $expected)
  {
    self::assertEquals(
      $expected
      , call_user_func_array([$subject, 'union'], $arrayLikeN)
      , 'Seq->union is expected to join the data with itself and the passed array likes'
    );
  }

  /** @coversNothing  */
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

  /**
   * @covers ::find
   */
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
   * @dataProvider subjectProvider
   * @coversNothing
   */
  public function test_walk_callback(testSubject $subject)
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
    self::assertEquals(count($subject), $ran);
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::walk
   */
  public function test_walk(testSubject $subject)
  {
    self::assertSame($subject, $subject->walk(noop));
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::head
   */
  public function test_head(testSubject $subject)
  {
    $headKeyR = array_slice($subject->keys()->toArray(), 0, 1, false);
    $expects = empty($headKeyR) ? null : $subject->toArray()[$headKeyR[0]];

    self::assertEquals($expects, $subject->head());
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::headMaybe
   */
  public function test_headMaybe(testSubject $subject)
  {
    $headKeyR = array_slice($subject->keys()->toArray(), 0, 1, false);
    $expects = empty($headKeyR) ? P\None() : P\Some($subject->toArray()[$headKeyR[0]]);

    $result = $subject->headMaybe();

    self::assertInstanceOf(P\Maybe::class, $result);
    self::assertEquals($expects, $result);
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::tail
   */
  public function test_tail(testSubject $subject)
  {
    $expects = testNew(array_slice($subject->toArray(), 1, null, true));

    self::assertEquals($expects, $subject->tail());
  }

  /**
   * @dataProvider indexOfProvider
   * @covers ::indexOf
   */
  public function test_indexOf(testSubject $haystack, $needle, $expected)
  {
    self::assertEquals(
      $expected
      , $haystack->indexOf($needle)
      , 'should yield the expected results for $needle in $haystack'
    );
  }

  /**
   * @dataProvider partitionProvider
   * @coversNothing
   */
  public function test_partition_callback(testSubject $subject)
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
   * @covers ::partition
   */
  public function test_partition(testSubject $subject, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->partition($hof)
      , 'Seq->partition should separate as expected the results of the $hof based on its Seq("false"=>Seq(value),"true"=>Seq(value)) value'
    );
  }

  /**
   * @dataProvider partitionWithKeyProvider
   * @coversNothing
   */
  public function test_partitionWithKey_callback(testSubject $subject)
  {
    $ran = 0;
    $subject->partitionWithKey(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return true;
    });
    self::assertEquals(count($subject), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider partitionWithKeyProvider
   * @covers ::partitionWithKey
   */
  public function test_partitionWithKey(testSubject $subject, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->partitionWithKey($hof)
      , 'should separate as expected the results of the $hof based on its Seq("false"=>Seq([key, value]),"true"=>Seq([key, value])) value'
    );
  }

  /**
   * @dataProvider groupProvider
   * @coversNothing
   */
  public function test_group_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->group(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return true;
    });
    self::assertEquals(count($subject), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider groupProvider
   * @covers ::group
   */
  public function test_group(testSubject $subject, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->group($hof)
      , 'applied to the key values given by the hof, should be a nested sequence of expected Seq'
    );
  }

  /**
   * @dataProvider groupWithKeyProvider
   * @coversNothing
   */
  public function test_groupWithKey_callback(testSubject $subject)
  {
    $ran = 0;

    $subject->groupWithKey(function () use ($subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($k), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return true;
    });
    self::assertEquals(count($subject), $ran, 'it should of ran on every entry');
  }

  /**
   * @dataProvider groupWithKeyProvider
   * @covers ::groupWithKey
   */
  public function test_groupWithKey(testSubject $subject, $hof, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->groupWithKey($hof)
      , 'applied to the key values given by the hof, should be a nested sequence of expected Seq'
    );
  }

  /**
   * @dataProvider dropProvider
   * @covers ::drop
   */
  public function test_drop(testSubject $subject, $number, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->drop($number)
      , 'Seq->drop of amount results are functionally equivilent to expected'
    );
  }

  /**
   * @dataProvider dropRightProvider
   * @covers ::dropRight
   */
  public function test_dropRight(testSubject $subject, $amount, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->dropRight($amount)
      , 'Seq->dropRight of amount should produce the Sequence expected'
    );
  }
  /**
   * @dataProvider takeProvider
   * @covers ::take
   */
  public function test_take(testSubject $subject, $amount, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->take($amount)
      , 'Seq->take of amount should yield expected'
    );
  }

  /**
   * @dataProvider takeRightProvider
   * @covers ::takeRight
   */
  public function test_takeRight(testSubject $subject, $amount, $expected)
  {
    self::assertEquals(
      $expected
      , $subject->takeRight($amount)
      , 'Seq->takeRight of amount should yield expected'
    );
  }

  /**
   * @dataProvider valueProvider
   * @covers ::toString
   */
  public function test_toString($value)
  {
    $glue = '!';

    self::assertEquals(implode($glue, self::toArray($value)), testSubject::from($value)->toString($glue));
  }

  /**
   * @dataProvider valueProvider
   * @covers ::toJson
   */
  public function test_toJson($value)
  {
    self::assertEquals(json_encode(self::toArray($value)), testSubject::from($value)->toJson());
  }

  /**
   * @dataProvider subjectProvider
   * @covers ::getIterator
   */
  public function test_forEach(testSubject $subject)
  {
    $idx = 0;
    $count = 0;

    /** @var testSubject $subjectKey Seriously phpstorm, you know this **face palm** */
    $subjectKey = $subject->keys();

    foreach ($subject as $key => $value) {
      self::assertSame($subjectKey($idx), $key);
      self::assertSame($subject($key), $value);
      $idx += 1;
      foreach ($subject as $k => $v) {
        $count += 1;
      }
    }
    self::assertEquals(count($subject), $idx);
    self::assertEquals(count($subject) ** 2, $count);
  }

  /**
   * @covers ::offsetExists
   */
  public function test_offsetExists($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $subject = testSubject::from($source);

    self::assertTrue($subject->offsetExists($offset));
    self::assertFalse($subject->offsetExists($notOffset));
  }

  /**
   * @covers  ::offsetGet
   */
  public function test_offsetGet($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $notOffset = $offset . 'nope';
    $subject = testSubject::from($source);

    self::assertSame($value, $subject->offsetGet($offset));
    self::assertNull($subject->offsetGet($notOffset));
  }

  /**
   * @covers ::offsetGetMaybe
   */
  public function test_offsetGetMaybe($value = true, $offset = '1')
  {
    $notOffset = $offset . 'nope';
    $source[$offset] = $value;
    $subject = testSubject::from($source);

    $result = $subject->offsetGetMaybe($offset);

    self::assertInstanceOf(P\Some::class, $result);
    self::assertSame($value, $result->getOrElse(!$value));
    self::assertInstanceOf(P\None::class, $subject->offsetGetMaybe($notOffset));
  }

  /**
   * @covers ::offsetGetAttempt
   */
  public function test_offsetGetAttempt($value = true, $offset = '1')
  {
    $source[$offset] = $value;
    $subject = testSubject::from($source);
    $notOffset = $offset . 'nope';

    $resultSuccess = $subject->offsetGetAttempt($offset);
    $resultFailure = $subject->offsetGetAttempt($notOffset);
    $resultException = $resultFailure->merge();

    self::assertInstanceOf(P\Success::class, $resultSuccess);
    self::assertSame($value, $resultSuccess->getOrElse(!$value));

    self::assertInstanceOf(P\Failure::class, $resultFailure);
    self::assertEquals($notOffset, $resultException->get());
    self::assertInstanceOf(P\exception\VacuousOffsetException::class, $resultException);
  }

  /**
   * @covers ::offsetSet
   */
  public function test_offsetSet($value = true)
  {
    $expectedInsertedAtLocation[1] = $value;
    $expectedInsertedAtEnd[] = $value;
    $subject = testSubject::from([]);

    $result = $subject->offsetSet(1, $value);
    $resultPush = $subject->offsetSet(null, $value);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($subject, $result);
    self::assertEquals($expectedInsertedAtLocation, $result->toArray());
    self::assertInstanceOf(testSubject::class, $resultPush);
    self::assertNotSame($subject, $resultPush);
    self::assertEquals($expectedInsertedAtEnd, $resultPush->toArray());
  }

  /**
   * @covers ::offsetAdjust
   */
  public function test_offsetAdjust()
  {
    $subject = testSubject::of(1, 2, 3);
    $offset = 1;
    $plus2 = function () use (&$ran, $subject, $offset) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($subject($offset), $v);
      self::assertTrue(is_int($k) || is_string($k));
      self::assertSame($subject, $t);

      $ran += 1;
      return $v + 2;
    };

    $result = $subject->offsetAdjust($offset, $plus2);

    self::assertEquals(1, $ran);
    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($subject, $result);
    self::assertEquals([1, 4, 3], $result->toArray());

    self::assertSame($subject, $subject->offsetAdjust(null, doNotRun));
  }

  /**
   * @covers ::offsetUnset
   */
  public function test_offsetUnset($source = [1, 2, 3], $offset = 2)
  {
    $subject = testNew($source);
    $expected = $source;
    unset($expected[$offset]);

    $result = $subject->offsetUnset($offset);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($result, $subject);
    self::assertEquals($expected, $result->toArray());
  }

  /**
   * @covers ::toArrayAccess
   */
  public function test_toArrayObject($source = [1, 2, 3])
  {
    $result = testNew($source)->toArrayAccess();

    self::assertInstanceOf(\ArrayObject::class, $result);
    self::assertEquals($source, $result->getArrayCopy());
  }

  /**
   * @param mixed $value
   * @return array
   */
  private static function toArray($value)
  {
    return is_array($value)
      ? $value
      : (($value instanceof P\FunctorInterface || $value instanceof \SplFixedArray)
        ? $value->toArray()
        : (($value instanceof \ArrayObject || $value instanceof \ArrayIterator)
          ? $value->getArrayCopy()
          : iterator_to_array($value)
        )
      );
  }

  public function valueProvider()
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
      , 'JustAIterator({one:1, two: 2})' => [new JustAnIterator(['one' => 1, 'two' => 2])]
      , 'S[]' => [testNew([])]
      , 'S[1,2,3]' => [testNew([1, 2, 3])]
    ];
  }

  public function subjectProvider()
  {
    return array_map(function ($value) {
      return [new testSubject($value[0])];
    }, $this->valueProvider());
  }

  public function nonEmptySubjectProvider()
  {
    return array_filter(
      $this->subjectProvider()
      , function ($args) {
      /** @var testSubject[] $args */
      return !$args[0]->isEmpty();
    }
    );
  }

  public function nestedValueProvider()
  {
    // Provides flatten operations with the solution
    return [
      'nested array' => [[[1, 2, 3], [4, 5, 6]], [1, 2, 3, 4, 5, 6]]
      , 'array with some' => [[P\Some(1), P\Some(2), P\Some(3)], [1, 2, 3]]
      , 'Seq of Seq' => [testSubject::of(testSubject::of(1, 2, 3), testSubject::of(4, 5, 6)), [1, 2, 3, 4, 5, 6]]
      , 'Seq of array' => [testSubject::of([1, 2, 3], [4, 5, 6]), [1, 2, 3, 4, 5, 6]]
    ];
  }

  public function numericSubjectProvider()
  {
    return [
      'empty' => [testSubject::from([])]
      , 'one' => [testSubject::of(1)]
      , 'from 1 to 9' => [testSubject::of(1, 2, 3, 4, 5, 6, 7, 8, 9)]
    ];
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

  public function subjectNumericRangeProvider()
  {
    return [
      'seq empty set' => [testSubject::of()]
      , 'seq from 1 to 4' => [testSubject::of(1, 2, 3, 4)]
      , 'seq from -2 to 2' => [testSubject::of(-2, -1, 0, 1, 2)]
      , 'seq from -4 to -1' => [testSubject::of(-4, -3, -2, -1)]
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

}


