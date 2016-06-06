<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/19/2016
 * Time: 2:52 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Exclusive as testSubject;
use const PHPixme\Exclusive as testConst;
use PHPixme\Undesired as lhs;
use PHPixme\Preferred as rhs;

/**
 * Class ExclusiveTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Exclusive
 */
class ExclusiveTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertFalse(function_exists(testSubject::class));
    self::assertSame(0, testSubject::shortName);
  }

  /**
   * @covers ::of
   */
  public function test_applicative($value = 1)
  {
    $result = testSubject::of($value);

    self::assertTrue($result->isRight());
    self::assertInstanceOf(testSubject::class, $result);
    self::assertInstanceOf(rhs::class, $result);
    self::assertEquals(rhs::of($value), $result);
  }

  /**
   * @covers ::ofLeft
   */
  public function test_left_applicative($value = 1)
  {
    $result = testSubject::ofLeft($value);

    self::assertTrue($result->isLeft());
    self::assertInstanceOf(testSubject::class, $result);
    self::assertInstanceOf(lhs::class, $result);
    self::assertEquals(lhs::of($value), $result);
  }

  /**
   * @covers ::ofRight
   */
  public function test_right_applicative($value = 1)
  {
    $result = testSubject::ofRight($value);

    self::assertTrue($result->isRight());
    self::assertInstanceOf(testSubject::class, $result);
    self::assertInstanceOf(rhs::class, $result);
    self::assertEquals(rhs::of($value), $result);
  }
}
