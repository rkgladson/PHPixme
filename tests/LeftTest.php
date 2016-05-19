<?php
namespace tests\PHPixme;

use PHPixme as P;

class LeftTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    $this->assertTrue(
      P\Left::class === P\Left
      , 'The constant for the class and the function should be equal'
    );
    $this->assertTrue(
      function_exists(P\Left)
      , 'The companion function should exist'
    );
    $this->assertInstanceOf(
      P\Left::class
      , P\Left(true)
      , 'The static companion should return the Class of its namesake'
    );
  }

  public function test_static_of($value = true)
  {
    $this->assertInstanceOf(P\Left::class, P\Left::of($value));
  }

  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\Left::class));
    $this->assertTrue(
      false !== array_search(P\ClosedTrait::class, $traits)
      , 'should be closed'
    );
  }

  public function test_patience(){
    $this->expectException(P\exception\MutationException::class);
    (new P\Left(0))->__construct(1);
  }
  
  public function test_handedness($value = true)
  {
    $Left = P\Left($value);
    $this->assertTrue($Left->isLeft());
    $this->assertFalse($Left->isRight());
    $this->assertInstanceOf(P\Some::class, $Left->left());
    $this->assertTrue($value === $Left->left()->get());
    $this->assertInstanceOf(P\None::class, $Left->right());
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
      $ran +=1;
      return $value;
    };

    P\Left($value)->fold($run, P\toss);
    $this->assertEquals(1, $ran);
  }

  public function test_fold_return($value = true)
  {
    $left = P\Left($value);
    $this->assertEquals(
      $value
      , $left->fold(P\I, P\toss)
      , 'a fold of the identity should return the value'
    );
  }

  public function test_swap($value = true)
  {
    $right = P\Left($value)->swap();
    $this->assertInstanceOf(P\Right::class, $right);
    $this->assertTrue($value === $right->merge());
  }

  public function test_flattenRight($value = true)
  {
    $left = P\Left($value);
    $this->assertTrue(
      $left === $left->flattenRight()
      , 'When called on its opposite, it should return itself.'
    );
  }

  public function test_flattenLeft($value = true)
  {
    $left = P\Left($value);
    $right = P\Right($value);
    $leftLeft = P\Left($left);
    $leftRight = P\Left($right);
    $this->assertTrue(
      $left === $leftLeft->flattenLeft()
      , 'When containing a left, it should return that left'
    );
    $this->assertTrue(
      $right === $leftRight->flattenLeft()
      , 'When containing a right, it should return that right'
    );
  }
  public function test_flattenLeft_contract_violated () {
    $this->expectException(\UnexpectedValueException::class);
    P\Left(true)->flattenLeft();
  }
}