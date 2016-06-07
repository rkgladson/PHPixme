<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Undesired as testSubject;
use function PHPixme\Undesired as testNew;
use const PHPixme\Undesired as testConst;

/**
 * Class UndesiredTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Undesired
 */
class UndesiredTest extends \PHPUnit_Framework_TestCase
{
  /** @coversNothing */
  public function test_constant()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
    self::assertNotEquals(getParent(testSubject::class)->getConstant(shortName), testSubject::shortName);
  }

  /** @coversNothing */
  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\LeftHandedTrait::class, $traits);
    self::assertContains(P\NothingCollectionTrait::class, $traits);
  }

  /** @coversNothing */
  public function test_patience($value = 1)
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject($value))->__construct($value);
  }

  /** @covers ::__construct */
  public function test_new($value = 1)
  {
    $result = new testSubject($value);

    self::assertAttributeSame($value, 'value', $result);
  }

  /** @covers PHPixme\Undesired */
  public function test_companion($value = 1)
  {
    $subject = testNew($value);
    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($value), $subject);
  }

  /** @covers ::of */
  public function test_applicative($value = 1)
  {
    $subject = testSubject::of($value);

    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($value), $subject);
  }

  /** @covers ::merge */
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

    self::assertInstanceOf(P\LeftHandSideType::class, $disjunction);
    self::assertTrue($disjunction->isLeft());
    self::assertFalse($disjunction->isRight());
  }

  /**
   * @covers ::flattenRight
   * @covers ::flattenLeft
   */
  public function test_flatten_handedly($value = 1)
  {
    $disjunction = testNew($value);
    $sibling = testSubject::ofRight($value);

    self::assertSame($disjunction, $disjunction->flattenRight());
    self::assertSame($disjunction, testNew($disjunction)->flattenLeft());
    self::assertSame($sibling, testNew($sibling)->flattenLeft());
  }

  /** @covers ::swap */
  public function test_swap($value = 1)
  {
    $displaced = testNew($value)->swap();

    self::assertInstanceOf(P\Preferred::class, $displaced);
    self::assertSame($value, $displaced->merge());
  }

  /** @covers ::count */
  public function test_count($value = 1)
  {
    $subject = testNew($value);

    self::assertEquals(0, $subject->count());
    self::assertEquals(0, count($subject));
  }

  /** @covers ::getIterator */
  public function test_iterator_interface($value = 1)
  {
    $ran = 0;
    foreach (testNew($value) as $meaningless) {
      $ran += 1;
    }

    self::assertEquals(0, $ran, 'should be considered empty');
  }

  /** @covers ::toArray */
  public function test_toArray($value = 1)
  {
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  /** @covers ::toUnbiasedDisjunction */
  public function test_toUnbiasedDisjunction($value = 1)
  {
    $disjunction = testNew($value)->toUnbiasedDisjunction();

    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $disjunction);
    self::assertInstanceOf(P\LeftHandSideType::class, $disjunction);
    self::assertTrue($disjunction->isLeft());
    self::assertFalse($disjunction->isRight());
    self::assertSame($value, $disjunction->merge());
  }
}
