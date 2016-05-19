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
    self::assertEquals(
      testSubject::class
      , testConst
      , 'there should be some constant that points to the class with the same name'
    );
    self::assertTrue(
      function_exists(P\Undesired::class)
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
  
  public function test_applicative($value = 1) {
    $disjunction = testSubject::of($value);
    self::assertInstanceOf(testSubject::class, $disjunction);
    self::assertTrue($disjunction->merge() === $value);
  }

  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    self::assertTrue(
      false !== array_search(P\ClosedTrait::class, $traits)
      , 'should be closed'
    );
    self::assertTrue(
      false !== array_search(P\LeftHandedTrait::class, $traits)
      , 'should be left handed'
    );
    self::assertTrue(
      false !== array_search(P\NothingCollectionTrait::class, $traits)
      , 'should be nothing'
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
    self::assertInstanceOf(P\LeftHandSideType::class, $disjunction);
    self::assertTrue($disjunction->isLeft());
    self::assertFalse($disjunction->isRight());
  }

  public function test_flatten_handedly($value = 1)
  {
    $disjunction = testNew($value);
    self::assertTrue($disjunction === $disjunction->flattenRight());
    
    $sibling = testSubject::ofRight($value);
    self::assertTrue($disjunction === testNew($disjunction)->flattenLeft());
    self::assertTrue($sibling === testNew($sibling)->flattenLeft());
  }
  
  public function test_swap($value = 1) {
    $changed = testNew($value)->swap();
    self::assertInstanceOf(P\Preferred::class, $changed);
    self::assertTrue($value === $changed->merge());
  }

  public function test_count($value = 1)
  {
    $disjunction = testNew($value);
    self::assertEquals(0, $disjunction->count());
    self::assertEquals(0, count($disjunction));
  }
  
  public function test_iterator_interface($value = 1) {
    $ran = 0;
    foreach (testNew($value) as $meaningless) {
      $ran+=1;
    }
    self::assertEquals(0, $ran, 'should be considered empty');
  }
  
  public function test_toArray($value = 1) {
    self::assertEquals([testSubject::shortName=>$value], testNew($value)->toArray());
  }

  public function test_toUnbiasedDisjunction($value = 1) {
    $disjunction = testNew($value)->toUnbiasedDisjunctionInterface();
    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $disjunction);
    self::assertInstanceOf(P\LeftHandSideType::class, $disjunction);
    self::assertTrue($disjunction->isLeft());
    self::assertFalse($disjunction->isRight());
    self::assertTrue($value === $disjunction->merge());
  }
}
