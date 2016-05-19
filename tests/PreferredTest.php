<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/19/2016
 * Time: 2:51 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Preferred as testSubject;
use function PHPixme\Preferred as testNew;
use const PHPixme\Preferred as testConst;

class PreferredTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    self::assertEquals(
      testSubject::class
      , testConst
      , 'there should be some constant that points to the class with the same name'
    );
    self::assertTrue(
      function_exists(testSubject::class)
      , 'there should be some function existing that has the same name'
    );
    self::assertNotEquals(
      (new \ReflectionClass(testSubject::class))->getParentClass()->getConstant(shortName)
      , testSubject::shortName
      , 'It should define its own shortName'
    );
  }

  public function test_companion($value = 1)
  {
    self::assertInstanceOf(testSubject::class, testNew($value));
  }

  public function test_merge_and_constructor($value = 1)
  {
    self::assertTrue((new testSubject($value))->merge() === $value);
  }

  public function test_applicative($value = 1)
  {
    $disjunction = testSubject::of($value);
    self::assertInstanceOf(testSubject::class, $disjunction);
    self::assertTrue($disjunction->merge() === $value);
  }

  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    $this->assertTrue(
      false !== array_search(P\ClosedTrait::class, $traits)
      , 'should be closed'
    );
    $this->assertTrue(
      false !== array_search(P\RightHandedTrait::class, $traits)
      , 'should be right handed'
    );
  }

  public function test_patience($value = 1)
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject($value))->__construct($value);
  }

  public function test_handedness($value = 1)
  {
    $disjunction = testNew($value);
    self::assertInstanceOf(P\RightHandSideType::class, $disjunction);
    self::assertFalse($disjunction->isLeft());
    self::assertTrue($disjunction->isRight());
  }


  public function test_flatten_handedly($value = 1)
  {
    $disjunction = testNew($value);
    self::assertTrue($disjunction === $disjunction->flattenLeft());

    $sibling = testSubject::ofLeft($value);
    self::assertTrue($disjunction === testNew($disjunction)->flattenRight());
    self::assertTrue($sibling === testNew($sibling)->flattenRight());
  }

  public function test_swap($value = 1)
  {
    $changed = testNew($value)->swap();
    self::assertInstanceOf(P\Undesired::class, $changed);
    self::assertTrue($value === $changed->merge());
  }

  public function test_count($value = 1)
  {
    $disjunction = testNew($value);
    self::assertEquals(1, $disjunction->count());
    self::assertEquals(1, count($disjunction));
  }

  public function test_iterator_interface($value = 1)
  {
    $ran = 0;
    foreach (testNew($value) as $k => $v) {
      self::assertTrue($k === $ran);
      self::assertTrue($v === $value);
      $ran += 1;
    }
    self::assertEquals(1, $ran, 'should be considered one of something');
  }

  public function test_toArray($value = 1)
  {
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  public function test_toUnbiasedDisjunction($value = 1)
  {
    $disjunction = testNew($value)->toUnbiasedDisjunctionInterface();
    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $disjunction);
    self::assertInstanceOf(P\RightHandSideType::class, $disjunction);
    self::assertFalse($disjunction->isLeft());
    self::assertTrue($disjunction->isRight());
    self::assertTrue($value === $disjunction->merge());
  }

  public function test_isEmpty($value = 1)
  {
    self::assertFalse(testNew($value)->isEmpty());
  }

  public function test_map_callback($value = 1)
  {
    $ran = 0;
    $self = testNew($value);
    testNew($value)->map(function () use ($self, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();
      self::assertTrue($value === $v);
      self::assertEquals(0, $k);
      self::assertTrue($self === $t);
      $ran += 1;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  public function test_map_return($value = 1)
  {
    $disjunction = testNew($value);
    $resultant = $disjunction->map(P\I);
    self::assertInstanceOf(testSubject::class, $resultant);
    self::assertEquals($resultant, $disjunction);
    self::assertFalse($resultant === $value);
  }

  public function test_fold_callback($value = 1, $startValue = 0)
  {
    $ran = 0;
    $self = testNew($value);
    testNew($value)->fold(function () use ($self, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();
      self::assertTrue($startValue === $s);
      self::assertTrue($value === $v);
      self::assertEquals(0, $k);
      self::assertTrue($self === $t);
      $ran+=1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  public function test_fold_return($value = 1, $startValue = 1)
  {
    self::assertTrue($startValue === testNew($value)->fold(P\I, $startValue));
  }

  public function test_foldRight_callback($value = 1, $startValue = 0)
  {
    $ran = 0;
    $self = testNew($value);
    testNew($value)->foldRight(function () use ($self, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();
      self::assertTrue($startValue === $s);
      self::assertTrue($value === $v);
      self::assertEquals(0, $k);
      self::assertTrue($self === $t);
      $ran+=1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  public function test_foldRight_return($value = 1, $startValue = 1)
  {
    self::assertTrue($startValue === testNew($value)->foldRight(P\I, $startValue));
  }
}
