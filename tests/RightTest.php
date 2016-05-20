<?php
namespace tests\PHPixme;

use PHPixme as P;
use function PHPixme\Right as testNew;

class RightTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    self::assertTrue(
      P\Right::class === P\Right
      , 'The constant for the class and the function should be equal'
    );
    self::assertTrue(
      function_exists(P\Right)
      , 'The companion function should exist'
    );
    self::assertInstanceOf(
      P\Right::class
      , P\Right(true)
      , 'The static companion should return the Class of its namesake'
    );
  }

  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\Right::class));
    self::assertTrue(
      false !== array_search(P\ClosedTrait::class, $traits)
      , 'should be closed'
    );
  }

  public function test_patience(){
    $this->expectException(P\exception\MutationException::class);
    (new P\Right(0))->__construct(1);
  }

  public function test_static_of($value = true)
  {
    self::assertInstanceOf(P\Right::class, P\Right::of($value));
  }

  public function test_handedness($value = true)
  {
    $right = P\Right($value);
    self::assertTrue($right->isRight());
    self::assertFalse($right->isLeft());
    self::assertInstanceOf(P\Some::class, $right->right());
    self::assertTrue($value === $right->right()->get());
    self::assertInstanceOf(P\None::class, $right->left());
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
    $right = P\Right($value);
    self::assertEquals(
      $value
      , $right->fold(P\toss, P\I)
      , 'a fold of the identity should return the value'
    );
  }

  public function test_swap($value = true)
  {
    $left = P\Right($value)->swap();
    self::assertInstanceOf(P\Left::class, $left);
    self::assertTrue($value === $left->merge());
  }

  public function test_flattenLeft($value = true)
  {
    $right = P\Right($value);
    self::assertTrue(
      $right === $right->flattenLeft()
      , 'When called on its opposite, it should return itself.'
    );
  }

  public function test_flattenRight($value = true)
  {
    $left = P\Left($value);
    $right = P\Right($value);
    $rightLeft = P\Right($left);
    $rightRight = P\Right($right);
    self::assertTrue(
      $left === $rightLeft->flattenRight()
      , 'When containing a left, it should return that left'
    );
    self::assertTrue(
      $right === $rightRight->flattenRight()
      , 'When containing a right, it should return that right'
    );
  }
  public function test_flattenRight_contract_violated () {
    $this->expectException(\UnexpectedValueException::class);
    P\Right(true)->flattenRight();
  }
}