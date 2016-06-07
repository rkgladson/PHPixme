<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Left as testSubject;
use function PHPixme\Left as testNew;
use const PHPixme\Left as testConst;
use PHPixme\Right as oppositeSubject;

/**
 * Class LeftTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Left
 */
class LeftTest extends \PHPUnit_Framework_TestCase
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
  public function test_attributes()
  {
    $reflection = new \ReflectionClass(testSubject::class);

    self::assertTrue($reflection->implementsInterface(P\LeftHandSideType::class));
  }

  /**
   * @coversNothing
   */
  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\LeftHandedTrait::class, $traits);
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
   * @covers PHPixme\Left
   */
  public function test_companion($value = null)
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
   * @covers ::isLeft
   * @covers ::isRight
   * @covers ::left
   * @covers ::right
   */
  public function test_handedness($value = true)
  {
    $Left = testNew($value);

    self::assertTrue($Left->isLeft());
    self::assertFalse($Left->isRight());
    self::assertInstanceOf(P\Some::class, $Left->left());
    self::assertSame($value, $Left->left()->get());
    self::assertInstanceOf(P\None::class, $Left->right());
  }

  /**
   * @covers ::merge
   */
  public function test_merge($value = true)
  {
    $subject = new testSubject($value);
    self::assertSame($value, $subject->merge());
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

    $subject->vFold($test, doNotRun);

    self::assertEquals(1, $ran);
  }

  /**
   * @covers ::fold
   */
  public function test_fold_return($value = true)
  {
    $left = testNew($value);
    self::assertSame($value, $left->vFold(P\I, doNotRun));
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
   * @covers ::flattenRight
   */
  public function test_flattenRight($value = true)
  {
    $subject = testNew($value);

    self::assertSame($subject, $subject->flattenRight());
  }

  /**
   * @covers ::flattenLeft
   */
  public function test_flattenLeft($value = true)
  {
    $left = testSubject::of($value);
    $right = oppositeSubject::of($value);


    self::assertSame($left, testNew($left)->flattenLeft());
    self::assertSame($right, testNew($right)->flattenLeft());
  }

  /**
   * @coversNothing
   */
  public function test_flattenLeft_contract_violated()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(true)->flattenLeft();
  }

  /**
   * @covers ::toBiasedDisjunction
   */
  public function test_toBiasedDisjunction($value = true)
  {
    $result = testSubject::of($value)->toBiasedDisjunction();

    self::assertInstanceOf(P\BiasedDisjunctionInterface::class, $result);
    self::assertInstanceOf(P\LeftHandSideType::class, $result);
    self::assertSame($value, $result->merge());
  }
}