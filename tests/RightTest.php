<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Right as testSubject;
use function PHPixme\Right as testNew;
use const PHPixme\Right as testConst;
use PHPixme\Left as oppositeSubject;

/**
 * Class RightTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Right
 */
class RightTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  /**
   * @coversNothing
   */
  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\RightHandedTrait::class, $traits);
  }

  /**
   * @covers ::__construct
   */
  public function test_new($value = null)
  {
    $result = new testSubject($value);

    self::assertAttributeSame($value, 'value', $result);
  }

  /**
   * @coversNothing
   */
  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(0))->__construct(1);
  }

  /**
   * @covers PHPixme\Right
   */
  public function test_companion($value = 1)
  {
    $results = testNew($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  /**
   * @covers ::of
   */
  public function test_applicative($value = true)
  {
    $results = testSubject::of($value);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertEquals(new testSubject($value), $results);
  }

  /**
   * @covers ::merge
   */
  public function test_merge($value = true) {
    self::assertSame($value, testNew($value)->merge());
  }

  /**
   * @covers ::isLeft
   * @covers ::isRight
   * @covers ::left
   * @covers ::right
   */
  public function test_handedness($value = true)
  {
    $subject = testNew($value);

    self::assertTrue($subject->isRight());
    self::assertFalse($subject->isLeft());
    self::assertInstanceOf(P\Some::class, $subject->right());
    self::assertSame($value, $subject->right()->get());
    self::assertInstanceOf(P\None::class, $subject->left());
  }

  /**
   * @coversNothing
   */
  public function test_fold_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);

    $test = function () use ($value, $subject, &$ran) {
      self::assertEquals(1, func_num_args());
      list ($v) = func_get_args();

      self::assertSame($value, $v);

      $ran += 1;
      return $value;
    };

    $subject->vFold(doNotRun, $test);

    self::assertEquals(1, $ran);
  }

  /**
   * @covers ::fold
   */
  public function test_fold_return($value = true)
  {
    self::assertSame($value, testNew($value)->vFold(doNotRun, identity));
  }

  /**
   * @covers ::swap
   */
  public function test_swap($value = true)
  {
    $result = testNew($value)->swap();

    self::assertInstanceOf(oppositeSubject::class, $result);
    self::assertSame($value, $result->merge());
  }

  /**
   * @covers ::flattenLeft
   */
  public function test_flattenLeft($value = true)
  {
    $subject = testNew($value);
    self::assertSame($subject, $subject->flattenLeft());
  }

  /**
   * @covers ::flattenRight
   */
  public function test_flattenRight($value = true)
  {
    $left = oppositeSubject::of($value);
    $right = testSubject::of($value);

    self::assertSame($left, testNew($left)->flattenRight());
    self::assertSame($right, testNew($right)->flattenRight());
  }

  /**
   * @coversNothing
   */
  public function test_flattenRight_contract_violated()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flattenRight();
  }

  /**
   * @covers ::toBiasedDisjunction
   */
  public function test_toBiasedDisjunction($value = true)
  {
    $result = testSubject::of($value)->toBiasedDisjunction();

    self::assertInstanceOf(P\BiasedDisjunctionInterface::class, $result);
    self::assertInstanceOf(P\RightHandSideType::class, $result);
    self::assertSame($value, $result->merge());
  }
}