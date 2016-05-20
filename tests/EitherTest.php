<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/27/2016
 * Time: 9:28 AM
 */

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
    self::assertSame(testSubject::class, P\Either);
    self::assertFalse(function_exists(testSubject::class)
      , 'The companion function should not exist for the class. Either should not have a static applicative.'
    );
  }

  /**
   * @dataProvider reflectionProvider
   * @param \ReflectionClass $reflection
   */
  public function test_not_collection(\ReflectionClass $reflection)
  {
    self::assertFalse(
      $reflection->implementsInterface(P\CollectionInterface::class)
      , 'Either, while having some collection like qualities, is an exclusive disjunction, not a collection.'
      . ' Collections only have one path of operation, while Either has two possible states of paths.'
    );
  }

  /**
   * @dataProvider reflectionProvider
   * @param \ReflectionClass $reflection
   */
  public function test_no_of(\ReflectionClass $reflection)
  {
    self::assertTrue(
      $reflection->implementsInterface(P\UnaryApplicativeInterface::class)
    );
    self::assertTrue(
      $reflection->getMethod('of')->isAbstract()
      , 'Either should not implement of, as it is not a collection of its own, but a disjunction'
    );
  }

  public function test_left_applicative($value = 1)
  {
    self::assertEquals(lhs::of($value), testSubject::ofLeft($value));
  }

  public function test_right_applicative($value = 1)
  {
    self::assertEquals(rhs::of($value), testSubject::ofRight($value));
  }


  public function reflectionProvider()
  {
    return [[new \ReflectionClass(testSubject::class)]];
  }
}