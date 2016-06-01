<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Undesired as testSubject;
use function PHPixme\Undesired as testNew;
use const PHPixme\Undesired as testConst;

class UndesiredTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
    self::assertNotEquals(getParent(testSubject::class)->getConstant(shortName), testSubject::shortName);
  }

  public function test_companion($value = 1)
  {
    self::assertInstanceOf(testSubject::class, testNew($value));
  }

  public function test_merge_and_constructor($value = 1)
  {
    self::assertSame($value, (new testSubject($value))->merge());
  }

  public function test_applicative($value = 1)
  {
    $disjunction = testSubject::of($value);

    self::assertInstanceOf(testSubject::class, $disjunction);
    self::assertSame($value, $disjunction->merge());
  }

  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\LeftHandedTrait::class, $traits);
    self::assertContains(P\NothingCollectionTrait::class, $traits);
  }

  public function test_patience($value = 1)
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject($value))->__construct($value);
  }

  public function test_handedness($value = 1)
  {
    $disjunction = testNew($value);

    self::assertInstanceOf(P\LeftHandSideType::class, $disjunction);
    self::assertTrue($disjunction->isLeft());
    self::assertFalse($disjunction->isRight());
  }

  public function test_flatten_handedly($value = 1)
  {
    $disjunction = testNew($value);
    $sibling = testSubject::ofRight($value);

    self::assertSame($disjunction, $disjunction->flattenRight());
    self::assertSame($disjunction, testNew($disjunction)->flattenLeft());
    self::assertSame($sibling, testNew($sibling)->flattenLeft());
  }

  public function test_swap($value = 1)
  {
    $displaced = testNew($value)->swap();

    self::assertInstanceOf(P\Preferred::class, $displaced);
    self::assertSame($value, $displaced->merge());
  }

  public function test_count($value = 1)
  {
    $subject = testNew($value);

    self::assertEquals(0, $subject->count());
    self::assertEquals(0, count($subject));
  }

  public function test_iterator_interface($value = 1)
  {
    $ran = 0;
    foreach (testNew($value) as $meaningless) {
      $ran += 1;
    }

    self::assertEquals(0, $ran, 'should be considered empty');
  }

  public function test_toArray($value = 1)
  {
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  public function test_toUnbiasedDisjunction($value = 1)
  {
    $disjunction = testNew($value)->toUnbiasedDisjunctionInterface();

    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $disjunction);
    self::assertInstanceOf(P\LeftHandSideType::class, $disjunction);
    self::assertTrue($disjunction->isLeft());
    self::assertFalse($disjunction->isRight());
    self::assertSame($value, $disjunction->merge());
  }
}
