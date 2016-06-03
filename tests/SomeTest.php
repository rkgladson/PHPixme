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

class SomeTest extends \PHPUnit_Framework_TestCase
{
  public function test_Some_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
  }

  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(1))->__construct(null);
  }

  public function test_companion($value = true)
  {
    $result = testNew($value);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  public function test_applicative($value = true)
  {
    $result = testSubject::of($value);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  public function test_contains($value = true, $notValue = false)
  {
    $subject = testNew($value);

    self::assertTrue($subject->contains($value));
    self::assertFalse($subject->contains($notValue));
  }

  public function test_exists($value = true)
  {
    $subject = testNew($value);

    self::assertTrue($subject->exists(bTrue));
    self::assertFalse($subject->exists(bFalse));
  }

  public function test_get($value = true)
  {
    self::assertSame($value, testNew($value)->get());
  }

  public function test_getOrElse($value = true, $default = false)
  {
    self::assertSame($value, testNew($value)->getOrElse($default));
  }

  public function test_isDefined($value = true)
  {
    self::assertTrue(testNew($value)->isDefined());
  }

  public function test_orNull($value = true)
  {
    self::assertSame($value, testNew($value)->orNull());
  }

  public function test_orElse($value = true)
  {
    $subject = testNew($value);
    
    self::assertEquals($subject, $subject->orElse(doNotRun));
  }

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

  public function test_fold_scenario_add($value = 1, $startVal = 1)
  {
    $add = function ($x, $y) {
      return $x + $y;
    };
    
    self::assertEquals($add($startVal, $value), testNew($value)->fold($add, $startVal));
  }

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

  public function test_foldRight_scenario_add($value = 1, $startVal = 1)
  {
    $add = function ($x, $y) {
      return $x + $y;
    };

    self::assertEquals($add($startVal, $value), (testNew($value)->foldRight($add, $startVal)));
  }


  public function test_reduce($value = true)
  {
    self::assertSame($value, testNew($value)->reduce(doNotRun));
  }

  public function test_reduceRight($value = true)
  {
    self::assertSame($value, testNew($value)->reduceRight(doNotRun));
  }

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

  public function test_map($value = true)
  {
    $subject = testNew($value);

    $result = $subject->map(identity);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($subject, $result);
    self::assertEquals($subject, $result);
  }

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

  public function test_flatMap_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatMap(identity);
  }

  public function test_flatMap($value = true)
  {
    $some = testSubject::of($value);
    $none = oppositeSubject::of($value);

    self::assertSame($some, testNew($some)->flatMap(identity));
    self::assertSame($none, testNew($none)->flatMap(identity));
  }

  public function test_flatten($value = true)
  {
    $some = testSubject::of($value);
    $none = oppositeSubject::of($value);

    self::assertSame($some, testNew($some)->flatten());
    self::assertSame($none, testNew($none)->flatten());
  }

  public function test_flatten_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatten();
  }

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

  public function test_filter_return($value = 1) {
    $subject = testSubject::of($value);

    self::assertSame($subject, $subject->filter(bTrue));
    self::assertSame(oppositeSubject::of($value), $subject->filter(bFalse));
  }

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

  public function test_filterNot_return($value = true)
  {
    $subject = testSubject::of($value);

    self::assertSame($subject, $subject->filterNot(bFalse));
    self::assertSame(oppositeSubject::of($value), $subject->filterNot(bTrue));
  }

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

  public function test_forAll_return ($value = 1) {
    $subject = testNew($value);

    self::assertTrue($subject->forAll(bTrue));
    self::assertFalse($subject->forAll(bFalse));
  }

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

  public function test_forNone_return($value = true)
  {
    $subject = testNew($value);

    self::assertFalse($subject->forNone(bTrue));
    self::assertTrue($subject->forNone(bFalse));
  }

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

  public function test_forSome_return($value = true)
  {
    $subject = testNew($value);

    self::assertTrue($subject->forSome(bTrue));
    self::assertFalse($subject->forSome(bFalse));
  }


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

  public function test_walk($value = true)
  {
    $subject = testNew($value);
    self::assertSame($subject, $subject->walk(noop));
  }

  public function test_toArray($value = true)
  {
    self::assertEquals([$value], testNew($value)->toArray());
  }

  public function test_toLeft($value = true)
  {
    $left = testNew($value)->toLeft(doNotRun);
    self::assertInstanceOf(P\Left::class, $left);
    self::assertSame($value, $left->merge());
  }

  public function test_toRight($value = true)
  {
    $right = testNew($value)->toRight(doNotRun);
    self::assertInstanceOf(P\Right::class, $right);
    self::assertSame($value, $right->merge());
  }

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

  public function test_find($value = true)
  {
    $subject = testNew($value);

    self::assertSame($subject, $subject->find(bTrue));
    self::assertInstanceOf(oppositeSubject::class, $subject->find(bFalse));
  }

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

  public function test_count($value = true)
  {
    $subject = testNew($value);

    self::assertEquals(1, count($subject));
    self::assertEquals(1, $subject->count());
  }
}
