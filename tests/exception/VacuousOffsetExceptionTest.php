<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/13/2016
 * Time: 11:13 AM
 */

namespace tests\PHPixme\exception;

use PHPixme\exception\MutationException;
use \PHPixme\exception\VacuousOffsetException as testSubject;
use PHPixme\Pot;

class VacuousOffsetExceptionTest extends \PHPUnit_Framework_TestCase
{
  public function test_static_creation($value = true)
  {
    self::assertInstanceOf(testSubject::class, testSubject::of($value));
  }

  public function test_get($value = true)
  {
    $empty = new testSubject($value);
    self::assertTrue($value, $empty->get());
  }

  public function test_countable($value = true)
  {
    $empty = new testSubject($value);
    self::assertEquals(1, $empty->count());
    self::assertEquals(1, count($empty));
  }

  public function test_toPot($value = true)
  {
    $empty = new testSubject($value);
    $emptyPot = $empty->toPot();
    self::assertInstanceOf(Pot::class, $emptyPot);
    self::assertEquals($empty->get(), $emptyPot->get());
    self::assertEquals($empty->getMessage(), $emptyPot->getMessage());
  }

  public function test_patience()
  {
    $this->expectException(MutationException::class);
    (new testSubject(0))->__construct(1);
  }
}
