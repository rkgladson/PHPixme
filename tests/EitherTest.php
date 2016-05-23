<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Either as testSubject;
use const PHPixme\Either as testConst;
use PHPixme\Left as lhs;
use PHPixme\Right as rhs;

class EitherTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    self::assertSame(testSubject::class, testConst);
    self::assertFalse(function_exists(testSubject::class));
  }

  public function test_aspects()
  {
    $subject  = new \ReflectionClass(testSubject::class);

    self::assertFalse($subject->implementsInterface(P\CollectionInterface::class));
    self::assertTrue($subject->implementsInterface(P\UnaryApplicativeInterface::class));
    self::assertTrue($subject->getMethod('of')->isAbstract());
  }

  public function test_left_applicative($value = 1)
  {
    self::assertEquals(lhs::of($value), testSubject::ofLeft($value));
  }

  public function test_right_applicative($value = 1)
  {
    self::assertEquals(rhs::of($value), testSubject::ofRight($value));
  }
}