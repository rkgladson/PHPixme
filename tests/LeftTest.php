<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Left as testSubject;
use function PHPixme\Left as testNew;
use const PHPixme\Left as testConst;
use PHPixme\Right as oppositeSubject;

class LeftTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    self::assertSame(testSubject::class, P\Left);
    self::assertTrue(function_exists(P\Left));
  }

  public function test_companion($value = null)
  {
    $result = testNew($value);
    self::assertInstanceOf(testSubject::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  public function test_static_of($value = true)
  {
    $result = testSubject::of($value);
    self::assertInstanceOf(testSubject::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  public function test_attributes()
  {
    $reflection = new \ReflectionClass(testSubject::class);
    self::assertTrue($reflection->implementsInterface(P\LeftHandSideType::class));
  }

  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\LeftHandedTrait::class, $traits);
  }

  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(0))->__construct(1);
  }

  public function test_handedness($value = true)
  {
    $Left = testNew($value);
    self::assertTrue($Left->isLeft());
    self::assertFalse($Left->isRight());
    self::assertInstanceOf(P\Some::class, $Left->left());
    self::assertTrue($value === $Left->left()->get());
    self::assertInstanceOf(P\None::class, $Left->right());
  }

  public function test_merge($value = true)
  {
    $subject = new testSubject($value);
    self::assertSame($value, $subject->merge());
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

    $subject->fold($test, doNotRun);

    self::assertEquals(1, $ran);
  }

  public function test_fold_return($value = true)
  {
    $left = testNew($value);
    self::assertSame($value, $left->fold(P\I, doNotRun));
  }

  public function test_swap($value = true)
  {
    $result = testNew($value)->swap();
    self::assertInstanceOf(oppositeSubject::class, $result);
    self::assertSame($value, $result->merge());
  }

  public function test_flattenRight($value = true)
  {
    $subject = testNew($value);
    self::assertSame($subject, $subject->flattenRight());
  }

  public function test_flattenLeft($value = true)
  {
    $left = testNew($value);
    $right = P\Right($value);
    $leftLeft = testNew($left);
    $leftRight = testNew($right);

    self::assertSame($left, $leftLeft->flattenLeft());
    self::assertSame($right, $leftRight->flattenLeft());
  }

  public function test_flattenLeft_contract_violated()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(true)->flattenLeft();
  }
}