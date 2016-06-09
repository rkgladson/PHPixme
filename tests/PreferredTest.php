<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Preferred as testSubject;
use function PHPixme\Preferred as testNew;
use const PHPixme\Preferred as testConst;
use PHPixme\exception\InvalidContentException as invalidContent;
use PHPixme\exception\InvalidReturnException as invalidReturn;

/**
 * Class PreferredTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Preferred
 */
class PreferredTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_constant()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
    self::assertNotEquals(getParent(testSubject::class)->getConstant(shortName), testSubject::shortName);
  }

  /**
   * @coversNothing
   */
  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\RightHandedTrait::class, $traits);
  }

  /**
   * @coversNothing
   */
  public function test_patience($value = 1)
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject($value))->__construct($value);
  }

  /**
   * @covers ::__construct
   */
  public function test_new($value = 1)
  {
    $result = new testSubject($value);

    self::assertAttributeSame($value, 'value', $result);
  }

  /**
   * @covers PHPixme\Preferred
   */
  public function test_companion($value = 1)
  {
    self::assertInstanceOf(testSubject::class, testNew($value));
  }

  /**
   * @covers ::of
   */
  public function test_applicative($value = 1)
  {
    $disjunction = testSubject::of($value);
    self::assertInstanceOf(testSubject::class, $disjunction);
    self::assertSame($value, $disjunction->merge());
  }

  /**
   * @covers ::merge
   */
  public function test_merge($value = 1)
  {
    self::assertSame($value, (new testSubject($value))->merge());
  }

  /**
   * @covers ::isLeft
   * @covers ::isRight
   */
  public function test_handedness($value = 1)
  {
    $disjunction = testNew($value);

    self::assertInstanceOf(P\RightHandSideType::class, $disjunction);
    self::assertFalse($disjunction->isLeft());
    self::assertTrue($disjunction->isRight());
  }

  /**
   * @covers ::flattenLeft
   * @covers ::flattenRight
   */
  public function test_flatten_handedly($value = 1)
  {
    $disjunction = testNew($value);
    $sibling = testSubject::ofLeft($value);

    self::assertSame($disjunction, $disjunction->flattenLeft());
    self::assertSame($disjunction, testNew($disjunction)->flattenRight());
    self::assertSame($sibling, testNew($sibling)->flattenRight());
  }

  /**
   * @covers ::swap
   */
  public function test_swap($value = 1)
  {
    $displaced = testNew($value)->swap();

    self::assertInstanceOf(P\Undesired::class, $displaced);
    self::assertSame($value, $displaced->merge());
  }

  /**
   * @covers ::count
   */
  public function test_count($value = 1)
  {
    $subject = testNew($value);

    self::assertEquals(1, $subject->count());
    self::assertEquals(1, count($subject));
  }

  /**
   * @covers ::getIterator
   */
  public function test_iterator_interface($value = 1)
  {
    $ran = 0;
    foreach (testNew($value) as $k => $v) {

      self::assertEquals($k, $ran);
      self::assertSame($v, $value);

      $ran += 1;
    }
    self::assertEquals(1, $ran, 'should be considered one of something');
  }

  /**
   * @covers ::toArray
   */
  public function test_toArray($value = 1)
  {
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  /**
   * @covers ::toUnbiasedDisjunction
   */
  public function test_toUnbiasedDisjunction($value = 1)
  {
    $resultant = testNew($value)->toUnbiasedDisjunction();

    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $resultant);
    self::assertInstanceOf(P\RightHandSideType::class, $resultant);
    self::assertFalse($resultant->isLeft());
    self::assertTrue($resultant->isRight());
    self::assertSame($value, $resultant->merge());
  }

  /**
   * @covers ::isEmpty
   */
  public function test_isEmpty($value = 1)
  {
    self::assertFalse(testNew($value)->isEmpty());
  }

  /**
   * @coversNothing
   */
  public function test_map_callback($value = 1)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->map(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::map
   */
  public function test_map_return($value = 1)
  {
    $subject = testNew($value);

    $resultant = $subject->map(identity);

    self::assertInstanceOf(testSubject::class, $resultant);
    self::assertEquals($resultant, $subject);
    self::assertNotSame($resultant, $value);
  }

  /** @covers ::apply */
  public function test_apply($value = null, $expected = 50)
  {
    $ran = 0;
    $testFn = function () use ($expected, &$ran) {
      $ran += 1;
      return $expected;
    };
    $functor = new testSubject($value);

    $result = testNew($testFn)->apply($functor);

    self::assertGreaterThan(0, $ran);
    self::assertEquals($functor->map($testFn), $result);
  }

  /** @coversNothing */
  public function test_apply_contract()
  {
    $this->expectException(invalidContent::class);
    testNew(null)->apply(testNew(null));
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_callback($value = 1)
  {
    $ran = 0;
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
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_contract_violation()
  {
    $this->expectException(invalidReturn::class);
    testNew(null)->flatMap(identity);
  }

  /**
   * @covers ::flatMap
   */
  public function test_flatMap_return($value = 1)
  {
    $disjunction = testNew($value);
    $sibling = testSubject::ofLeft($value);

    self::assertSame($disjunction, testNew($disjunction)->flatMap(identity));
    self::assertSame($sibling, testNew($sibling)->flatMap(identity));
  }

  /**
   * @covers ::flatten
   */
  public function test_flatten()
  {
    $self = testNew(null);
    $sibling = testSubject::ofLeft(null);

    self::assertSame($self, testNew($self)->flatten());
    self::assertSame($sibling, testNew($sibling)->flatten());
  }

  /**
   * @coversNothing
   */
  public function test_flatten_contract() {
    $this->expectException(invalidContent::class);
    testNew(null)->flatten();
  }

  /**
   * @coversNothing
   */
  public function test_fold_callback($value = 1, $startValue = 0)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->fold(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertSame($startValue, $s);
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::fold
   */
  public function test_fold_return($value = 1, $startValue = 1)
  {
    self::assertSame($startValue, testNew($value)->fold(identity, $startValue));
  }

  /**
   * @coversNothing
   */
  public function test_foldRight_callback($value = 1, $startValue = 0)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->foldRight(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertSame($startValue, $s);
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::foldRight
   */
  public function test_foldRight_return($value = 1, $startValue = 1)
  {
    self::assertSame($startValue, testNew($value)->foldRight(identity, $startValue));
  }

  /** @coversNothing */
  public function test_forAll_callback($value = 1)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->forAll(function () use ($value, $subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /** @covers ::forAll */
  public function test_forAll_return($value = 1)
  {
    $subject = testNew($value);

    self::assertTrue($subject->forAll(bTrue));
    self::assertFalse($subject->forAll(bFalse));
  }

  /** @coversNothing */
  public function test_forNone_callback($value = 1)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->forNone(function () use ($value, $subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /** @covers ::forNone */
  public function test_forNone_return($value = 1)
  {
    $subject = testNew($value);

    self::assertFalse($subject->forNone(bTrue));
    self::assertTrue($subject->forNone(bFalse));
  }

  /** @coversNothing */
  public function test_forSome_callback($value = 1)
  {
    $ran = 0;
    $self = testNew($value);

    $self->forSome(function () use ($value, $self, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($self, $t);

      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /** @covers ::forSome */
  public function test_forSome_return($value = 1)
  {
    $subject = testNew($value);

    self::assertTrue($subject->forSome(bTrue));
    self::assertFalse($subject->forSome(bFalse));
  }

  /** @coversNothing */
  public function test_find_callback($value = 1)
  {
    $ran = 0;
    $subject = testNew($value);
    $subject->find(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /** @covers ::find */
  public function test_find_return($value = 1)
  {
    $subject = testNew($value);

    $found = $subject->find(bTrue);
    $missing = $subject->find(bFalse);

    self::assertInstanceOf(P\Some::class, $found);
    self::assertSame($value, $found->get());
    self::assertInstanceOf(P\None::class, $missing);
  }

  /** @coversNothing */
  public function test_walk_callback($value = 1)
  {
    $ran = 0;
    $self = testNew($value);

    $self->walk(function () use ($self, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($self, $t);

      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /** @covers ::walk */
  public function test_walk_return($value = 1)
  {
    $subject = testNew($value);

    self::assertSame($subject, $subject->walk(noop));
  }
}
