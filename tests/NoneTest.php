<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/8/2016
 * Time: 1:18 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\None as testSubject;
use function PHPixme\None as testNew;
use const PHPixme\None as testConst;
use PHPixme\Some as oppositeSubject;

/**
 * Class NoneTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\None
 */
class NoneTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_None_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  /**
   * @covers PHPixme\None
   */
  public function test_None_companion()
  {
    self::assertInstanceOf(testSubject::class, testNew());
  }


  /**
   * @coversNothing
   */
  public function test_aspects()
  {
    $subjectReflection = new \ReflectionClass(testSubject::class);

    self::assertTrue($subjectReflection->getMethod('__construct')->isProtected());
    self::assertTrue($subjectReflection->getMethod('__clone')->isProtected());
  }


  /**
   * @coversNothing
   */
  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\NothingCollectionTrait::class, $traits);
  }

  /**
   * @covers ::getInstance
   * @covers ::__construct
   */
  public function test_None_static_getInstance()
  {
    $result = testSubject::getInstance();

    self::assertInstanceOf(testSubject::class, $result);
    self::assertSame($result, testSubject::getInstance());
  }


  /**
   * @covers ::of
   */
  public function test_applicative($value = 1)
  {
    self::assertSame(testNew(), testSubject::of($value));
  }


  /**
   * @covers ::contains
   */
  public function test_contains($value = 1)
  {
    self::assertFalse(testNew()->contains($value));
  }

  /**
   * @covers ::exists
   */
  public function test_exists()
  {
    self::assertFalse(testNew()->exists(doNotRun));
  }

  /**
   * @covers ::forAll
   */
  public function test_forAll()
  {
    self::assertTrue(testNew()->forAll(doNotRun));
  }

  /**
   * @covers ::forNone
   */
  public function test_forNone()
  {
    self::assertTrue(testNew()->forNone(doNotRun));
  }

  /**
   * @covers ::forSome
   */
  public function test_forSome()
  {
    self::assertFalse(testNew()->forSome(doNotRun));
  }

  /**
   * @covers ::get
   */
  public function test_get()
  {
    $this->expectException(\Exception::class);
    testNew()->get();
  }

  /**
   * @covers ::getOrElse
   */
  public function test_getOrElse($default = true)
  {
    self::assertSame($default, testNew()->getOrElse($default));
  }

  /**
   * @covers ::isDefined
   */
  public function test_isDefined()
  {
    self::assertFalse(testNew()->isDefined());
  }

  /**
   * @covers ::orNull
   */
  public function test_orNull()
  {
    self::assertNull(testNew()->orNull());
  }

  /**
   * @covers ::orElse
   */
  public function test_orElse($value = 1)
  {
    $some = oppositeSubject::of($value);
    $defaultToSome = function () use ($some) {
      return $some;
    };
    $subject = testNew();

    self::assertSame($some, $subject->orElse($defaultToSome));
    self::assertSame($subject, $subject->orElse(testSubject::class));
  }

  /**
   * @coversNothing
   */
  public function test_orElse_contract_broken()
  {
    $this->expectException(\Exception::class);
    testNew()->orElse(noop);
  }

  /**
   * @covers ::toSeq
   */
  public function test_toSeq()
  {
    $result = testNew()->toSeq();

    self::assertInstanceOf(P\Seq::class, $result);
    self::assertTrue($result->isEmpty());
  }

  /**
   * @covers ::reduce
   */
  public function test_reduce()
  {
    $this->expectException(\LengthException::class);
    testNew()->reduce(noop);
  }

  /**
   * @covers ::reduceRight
   */
  public function test_reduceRight()
  {
    $this->expectException(\LengthException::class);
    testNew()->reduceRight(noop);
  }

  /**
   * @covers ::fold
   */
  public function test_fold($startVal = true)
  {
    self::assertSame($startVal, testNew()->fold(doNotRun, $startVal));
  }

  /**
   * @covers ::foldRight
   */
  public function test_foldRight($startVal = true)
  {
    self::assertSame($startVal, testNew()->foldRight(doNotRun, $startVal));
  }

  /**
   * @covers ::map
   */
  public function test_map()
  {
    $subject = testNew();

    self::assertSame($subject, $subject->map(doNotRun));
  }

  /**
   * @covers ::apply
   */
  public function test_apply() {
    $subject = testNew();

    self::assertSame($subject, $subject->apply(P\Some(null)));
  }

  /**
   * @covers ::filter
   */
  public function test_filter()
  {
    $subject = testNew();

    self::assertSame($subject, $subject->filter(doNotRun));
  }

  /**
   * @covers ::filterNot
   */
  public function test_filterNot()
  {
    $subject = testNew();

    self::assertSame($subject, $subject->filterNot(doNotRun));
  }

  /**
   * @covers ::walk
   */
  public function test_walk()
  {
    $times = 0;

    $result = testNew()->walk(function () use (&$times) {
      $times += 1;
    });

    self::assertSame(testNew(), $result);
    self::assertEquals(0, $times);
  }

  /**
   * @covers ::toArray
   */
  public function test_toArray()
  {
    $result = testNew()->toArray();

    self::assertTrue(is_array($result));
    self::assertEquals([], $result);
  }

  /**
   * @covers ::toLeft
   */
  public function test_toLeft($value = true)
  {
    $ran = 0;
    $getVoidValue = function () use ($value, &$ran) {
      self::assertEquals(0, func_num_args());

      $ran += 1;
      return $value;
    };

    $left = testNew()->toLeft($getVoidValue);

    self::assertEquals(1, $ran);
    self::assertInstanceOf(P\Right::class, $left);
    self::assertSame($value, $left->merge());
  }

  /**
   * @covers ::toRight
   */
  public function test_toRight($value = true)
  {
    $ran = 0;
    $getVoidValue = function () use ($value, &$ran) {
      self::assertEquals(0, func_num_args());

      $ran += 1;
      return $value;
    };

    $right = testNew()->toRight($getVoidValue);

    self::assertEquals(1, $ran);
    self::assertInstanceOf(P\Left::class, $right);
    self::assertSame($value, $right->merge());
  }

  /**
   * @covers ::isEmpty
   */
  public function test_isEmpty()
  {
    self::assertTrue(testNew()->isEmpty());
  }

  /**
   * @covers ::find
   */
  public function test_find()
  {
    self::assertInstanceOf(testSubject::class, testNew()->find(doNotRun));
  }

  /**
   * @covers ::flatten
   */
  public function test_flatten()
  {
    $subject = testNew();

    self::assertSame($subject, $subject->flatten());
  }

  /**
   * @covers ::flatMap
   */
  public function test_flatMap()
  {
    $subject = testNew();

    self::assertSame($subject, $subject->flatMap(doNotRun));
  }


  /**
   * @covers ::getIterator
   */
  public function test_traversable_interface()
  {
    // time to test if the interface works
    $times = 0;
    foreach (testNew() as $key => $value) {
      $times += 1;
    }

    self::assertEquals(0, $times);
  }

  /**
   * @covers ::count
   */
  public function test_count()
  {
    $subject = testNew();

    self::assertEquals(0, count($subject));
    self::assertEquals(0, $subject->count());
  }
}
