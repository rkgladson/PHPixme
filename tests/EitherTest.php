<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Either as testSubject;
use const PHPixme\Either as testConst;
use PHPixme\Left as lhs;
use PHPixme\Right as rhs;

/**
 * Class EitherTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Either
 */
class EitherTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_constants()
  {
    self::assertSame(testSubject::class, testConst);
    self::assertFalse(function_exists(testSubject::class));
  }

  /**
   * @coversNothing
   */
  public function test_aspects()
  {
    $subject  = new \ReflectionClass(testSubject::class);

    self::assertFalse($subject->implementsInterface(P\CollectionInterface::class));
    self::assertTrue($subject->implementsInterface(P\UnaryApplicativeInterface::class));
    self::assertTrue($subject->getMethod('of')->isAbstract());
  }
  
  public function test_traits() {
    $subjectReflection = new \ReflectionClass(testSubject::class);
    $subjectTraits = $subjectReflection->getTraitNames();
    $allTraits = getAllTraits($subjectReflection);

    self::assertContains(P\RootTypeTrait::class, $subjectTraits);

    self::assertContains(P\ClosedTrait::class, $allTraits);
  }

  /**
   * @covers ::ofLeft
   */
  public function test_left_applicative($value = 1)
  {
    self::assertEquals(lhs::of($value), testSubject::ofLeft($value));
  }

  /**
   * @covers ::ofRight
   */
  public function test_right_applicative($value = 1)
  {
    self::assertEquals(rhs::of($value), testSubject::ofRight($value));
  }
}