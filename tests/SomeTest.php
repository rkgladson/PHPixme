<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/8/2016
 * Time: 3:11 PM
 */
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Some as testSubject;
use function PHPixme\Some as testNew;
use const PHPixme\Some as testConst;
use PHPixme\None as oppositeSubject;

/**
 * Class SomeTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Some
 */
class SomeTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_Some_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  /**
   * @coversNothing
   */
  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
  }

  /**
   * @coversNothing
   */
  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(1))->__construct(null);
  }

  /**
   * @covers PHPixme\Some
   */
  public function test_companion($value = true)
  {
    $result = testNew($value);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  /**
   * @covers ::of
   */
  public function test_applicative($value = true)
  {
    $result = testSubject::of($value);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  /**
   * @covers ::contains
   */
  public function test_contains($value = true, $notValue = false)
  {
    $subject = testNew($value);

    self::assertTrue($subject->contains($value));
    self::assertFalse($subject->contains($notValue));
  }

  /**
   * @covers ::exists
   */
  public function test_exists($value = true)
  {
    $subject = testNew($value);

    self::assertTrue($subject->exists(bTrue));
    self::assertFalse($subject->exists(bFalse));
  }

  /**
   * @covers ::isDefined
   */
  public function test_isDefined($value = true)
  {
    self::assertTrue(testNew($value)->isDefined());
  }

  /**
   * @covers ::get
   */
  public function test_get($value = true)
  {
    self::assertSame($value, testNew($value)->get());
  }

  /**
   * @covers ::getOrElse
   */
  public function test_getOrElse($value = true, $default = false)
  {
    self::assertSame($value, testNew($value)->getOrElse($default));
  }


  /**
   * @covers ::orNull
   */
  public function test_orNull($value = true)
  {
    self::assertSame($value, testNew($value)->orNull());
  }

  public function test_orElse($value = true)
  {
    $subject = testNew($value);
    
    self::assertEquals($subject, $subject->orElse(doNotRun));
  }

  /**
   * @covers ::toSeq
   */
  public function test_toSeq($value = true)
  {
    $result = testNew($value)->toSeq();

    self::assertInstanceOf(P\Seq::class, $result);
    self::assertEquals(new P\Seq([$value]), $result);
  }


  /**
   * @coversNothing 
   */
  public function test_fold_callback($value = true, $startValue = null)
  {
    $ran = 0;
    $subject = testNew($value);
    
    $subject->fold(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertSame($startValue, $s);
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject,  $t);
      
      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::fold
   */
  public function test_fold_scenario_add($value = 1, $startVal = 1)
  {
    $add = function ($x, $y) {
      return $x + $y;
    };
    
    self::assertEquals($add($startVal, $value), testNew($value)->fold($add, $startVal));
  }

  /**
   * @coversNothing
   */
  public function test_foldRight_callback($value = true, $startValue = null)
  {
    $ran = 0;
    $subject = testNew($value);
    
    $subject->foldRight(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertSame($startValue, $s);
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject,  $t);

      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::foldRight
   */
  public function test_foldRight_scenario_add($value = 1, $startVal = 1)
  {
    $add = function ($x, $y) {
      return $x + $y;
    };

    self::assertEquals($add($startVal, $value), (testNew($value)->foldRight($add, $startVal)));
  }


  /**
   * @covers ::reduce
   */
  public function test_reduce($value = true)
  {
    self::assertSame($value, testNew($value)->reduce(doNotRun));
  }

  /**
   * @covers ::reduceRight
   */
  public function test_reduceRight($value = true)
  {
    self::assertSame($value, testNew($value)->reduceRight(doNotRun));
  }

  /**
   * @coversNothing
   */
  public function test_map_callback($value = true)
  {
    $subject = testNew($value);

    $subject->map(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::map
   */
  public function test_map($value = true)
  {
    $subject = testNew($value);

    $result = $subject->map(identity);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($subject, $result);
    self::assertEquals($subject, $result);
  }

  /**
   * @covers ::apply
   */
  public function test_apply() {
    $ran = 0;
    $expected = 50;
    $testFn = function () use (&$ran, $expected) {
      $ran += 1;
      return $expected;
    };
    $functor = P\Some(1);
    $subject = testNew($testFn);

    $result = $subject->apply($functor);

    self::assertGreaterThan(0, $ran);
    self::assertEquals($functor->map($testFn), $result);
  }

  /**
   * @coversNothing
   */
  public function test_apply_contract() {
    $this->expectException(P\exception\InvalidContentException::class);
    testNew(null)->apply(testNew(null));
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_callback($value = true)
  {
    $contents = testNew($value);
    $subject = testNew($contents);

    $subject->flatMap(function () use ($subject, $contents, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($contents, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatMap(identity);
  }

  /**
   * @covers ::flatMap
   */
  public function test_flatMap($value = true)
  {
    $some = testSubject::of($value);
    $none = oppositeSubject::of($value);

    self::assertSame($some, testNew($some)->flatMap(identity));
    self::assertSame($none, testNew($none)->flatMap(identity));
  }

  /**
   * @covers ::flatten
   */
  public function test_flatten($value = true)
  {
    $some = testSubject::of($value);
    $none = oppositeSubject::of($value);

    self::assertSame($some, testNew($some)->flatten());
    self::assertSame($none, testNew($none)->flatten());
  }

  /**
   * @coversNothing
   */
  public function test_flatten_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatten();
  }

  /**
   * @coversNothing
   */
  public function test_filter_callback($value = true)
  {
    $subject = testNew($value);

    $subject->filter(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::filter
   */
  public function test_filter_return($value = 1) {
    $subject = testSubject::of($value);

    self::assertSame($subject, $subject->filter(bTrue));
    self::assertSame(oppositeSubject::of($value), $subject->filter(bFalse));
  }

  /**
   * @coversNothing
   */
  public function test_filterNot_callback($value = true)
  {
    $subject = testNew($value);

    $subject->filterNot(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::filterNot
   */
  public function test_filterNot_return($value = true)
  {
    $subject = testSubject::of($value);

    self::assertSame($subject, $subject->filterNot(bFalse));
    self::assertSame(oppositeSubject::of($value), $subject->filterNot(bTrue));
  }

  /**
   * @coversNothing
   */
  public function test_forAll_callback($value = true)
  {
    $subject = testNew($value);

    $subject->forAll(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::forAll
   */
  public function test_forAll_return ($value = 1) {
    $subject = testNew($value);

    self::assertTrue($subject->forAll(bTrue));
    self::assertFalse($subject->forAll(bFalse));
  }

  /**
   * @coversNothing
   */
  public function test_forNone_callback($value = true)
  {
    $subject = testNew($value);

    $subject->forNone(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::forNone
   */
  public function test_forNone_return($value = true)
  {
    $subject = testNew($value);

    self::assertFalse($subject->forNone(bTrue));
    self::assertTrue($subject->forNone(bFalse));
  }

  /**
   * @coversNothing
   */
  public function test_forSome_callback($value = true)
  {
    $subject = testNew($value);

    $subject->forSome(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::forSome
   */
  public function test_forSome_return($value = true)
  {
    $subject = testNew($value);

    self::assertTrue($subject->forSome(bTrue));
    self::assertFalse($subject->forSome(bFalse));
  }


  /**
   * @coversNothing
   */
  public function test_walk_callback($value = true)
  {
    $subject = testNew($value);

    $subject->walk(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::walk
   */
  public function test_walk($value = true)
  {
    $subject = testNew($value);
    self::assertSame($subject, $subject->walk(noop));
  }

  /**
   * @covers ::toArray
   */
  public function test_toArray($value = true)
  {
    self::assertEquals([$value], testNew($value)->toArray());
  }

  /**
   * @covers ::toLeft
   */
  public function test_toLeft($value = true)
  {
    $left = testNew($value)->toLeft(doNotRun);
    self::assertInstanceOf(P\Left::class, $left);
    self::assertSame($value, $left->merge());
  }

  /**
   * @covers ::toRight
   */
  public function test_toRight($value = true)
  {
    $right = testNew($value)->toRight(doNotRun);
    self::assertInstanceOf(P\Right::class, $right);
    self::assertSame($value, $right->merge());
  }

  /**
   * @coversNothing
   */
  public function test_find_callback($value = true)
  {
    $subject = testNew($value);

    $subject->find(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::find
   */
  public function test_find($value = true)
  {
    $subject = testNew($value);

    self::assertSame($subject, $subject->find(bTrue));
    self::assertInstanceOf(oppositeSubject::class, $subject->find(bFalse));
  }

  /**
   * @covers ::getIterator
   */
  function test_forEach($value = true)
  {
    $ran = 0;
    $subject = testNew($value);

    foreach ($subject as $key => $val) {
      self::assertSame($value, $val);
      self::assertEquals($ran, $key);

      $ran += 1;
    }

    self::assertEquals(1, $ran);
  }

  /**
   * @covers ::count
   */
  public function test_count($value = true)
  {
    $subject = testNew($value);

    self::assertEquals(1, count($subject));
    self::assertEquals(1, $subject->count());
  }
}
