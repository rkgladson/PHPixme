<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Right as testSubject;
use function PHPixme\Right as testNew;
use const PHPixme\Right as testConst;
use PHPixme\Left as oppositeSubject;

class RightTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\RightHandedTrait::class, $traits);
  }

  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(0))->__construct(1);
  }

  public function test_companion($value = 1)
  {
    $results = testNew($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  public function test_applicative($value = true)
  {
    $results = testSubject::of($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  public function test_handedness($value = true)
  {
    $subject = testNew($value);

    self::assertTrue($subject->isRight());
    self::assertFalse($subject->isLeft());
    self::assertInstanceOf(P\Some::class, $subject->right());
    self::assertSame($value, $subject->right()->get());
    self::assertInstanceOf(P\None::class, $subject->left());
  }

  public function test_fold_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);

    $test = function () use ($value, $subject, &$ran) {
      self::assertEquals(2, func_num_args());
      list ($v, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertSame($subject, $t);

      $ran += 1;
      return $value;
    };

    $subject->fold(doNotRun, $test);

    self::assertEquals(1, $ran);
  }

  public function test_fold_return($value = true)
  {
    self::assertSame($value, testNew($value)->fold(doNotRun, identity));
  }

  public function test_swap($value = true)
  {
    $result = testNew($value)->swap();

    self::assertInstanceOf(oppositeSubject::class, $result);
    self::assertSame($value, $result->merge());
  }

  public function test_flattenLeft($value = true)
  {
    $subject = testNew($value);
    self::assertSame($subject, $subject->flattenLeft());
  }

  public function test_flattenRight($value = true)
  {
    $left = oppositeSubject::of($value);
    $right = testSubject::of($value);

    self::assertSame($left, testNew($left)->flattenRight());
    self::assertSame($right, testNew($right)->flattenRight());
  }

  public function test_flattenRight_contract_violated()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flattenRight();
  }

  public function test_toBiasedDisJunctionInterface($value = true)
  {
    $result = testSubject::of($value)->toBiasedDisJunctionInterface();

    self::assertInstanceOf(P\BiasedDisjunctionInterface::class, $result);
    self::assertInstanceOf(P\RightHandSideType::class, $result);
    self::assertSame($value, $result->merge());
  }
}