<?php
namespace tests\PHPixme;

use PHPixme as P;

class RightTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    $this->assertTrue(
      P\Right::class === P\Right
      , 'The constant for the class and the function should be equal'
    );
    $this->assertTrue(
      function_exists(P\Right)
      , 'The companion function should exist'
    );
    $this->assertInstanceOf(
      P\Right::class
      , P\Right(true)
      , 'The static companion should return the Class of its namesake'
    );
  }

  public function test_static_of($value = true)
  {
    $this->assertInstanceOf(P\Right::class, P\Right::of($value));
  }

  public function test_handedness($value = true)
  {
    $right = P\Right($value);
    $this->assertTrue($right->isRight());
    $this->assertFalse($right->isLeft());
    $this->assertInstanceOf(P\Some::class, $right->right());
    $this->assertTrue($value === $right->right()->get());
    $this->assertInstanceOf(P\None::class, $right->left());
  }

  public function test_fold_callback($value = true)
  {
    $ran = 0;
    $run = function () use ($value, &$ran) {
      $this->assertEquals(
        1, func_num_args()
        , 'the callback should receive one argument'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'the first argument should be exactly the value that was contained.'
      );
      $ran += 1;
      return $value;
    };

    P\Right($value)->fold(P\toss, $run);
    $this->assertEquals(1, $ran);
  }

  public function test_fold_return($value = true)
  {
    $right = P\Right($value);
    $this->assertEquals(
      $value
      , $right->fold(P\toss, P\I)
      , 'a fold of the identity should return the value'
    );
  }

  public function test_swap($value = true)
  {
    $left = P\Right($value)->swap();
    $this->assertInstanceOf(P\Left::class, $left);
    $this->assertTrue($value === $left->merge());
  }

  public function test_flattenLeft($value = true)
  {
    $right = P\Right($value);
    $this->assertTrue(
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
    $this->assertTrue(
      $left === $rightLeft->flattenRight()
      , 'When containing a left, it should return that left'
    );
    $this->assertTrue(
      $right === $rightRight->flattenRight()
      , 'When containing a right, it should return that right'
    );
  }
  public function test_flattenRight_contract_violated () {
    $this->expectException(\UnexpectedValueException::class);
    P\Right(true)->flattenRight();
  }
}