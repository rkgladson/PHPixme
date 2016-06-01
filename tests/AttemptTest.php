<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Attempt as testSubject;
use const PHPixme\Attempt as testConst;
use function PHPixme\Attempt as testNew;
use PHPixme\Success as rhs;
use function PHPixme\Success as rhsNew;
use PHPixme\Failure as lhs;
use function PHPixme\Failure as lhsNew;

class AttemptTest extends \PHPUnit_Framework_TestCase
{

  public function test_Attempt_constants()
  {
    self::assertTrue(testSubject::class === testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  public function test_companion_returns_left()
  {
    $exception = new \Exception();

    $thrown = testNew(function () use ($exception) {
      throw $exception;
    });

    self::assertInstanceOf(lhs::class, $thrown);
    self::assertEquals(new lhs ($exception), $thrown);
  }

  public function test_companion_returns_right()
  {
    $value = 1;
    $result = testNew(function () use ($value) {
      return $value;
    });
    self::assertInstanceOf(rhs::class, $result);
    self::assertEquals(new rhs($value), $result);
  }

  public function test_applicative_return_left()
  {
    $exception = new \Exception();

    $thrown = testSubject::of(function () use ($exception) {
      throw $exception;
    });

    self::assertInstanceOf(lhs::class, $thrown);
    self::assertEquals(new lhs ($exception), $thrown);
  }

  public function test_right_applicative_return()
  {
    $value = 1;

    $result = testSubject::ofRight($value);

    self::assertInstanceOf(rhs::class, $result);
    self::assertEquals(new rhs($value), $result);
  }

  public function test_left_applicative_return()
  {
    $exception = new \Exception();

    $thrown = testSubject::ofLeft($exception);

    self::assertInstanceOf(lhs::class, $thrown);
    self::assertEquals(new lhs($exception), $thrown);
  }
}
