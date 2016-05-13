<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/13/2016
 * Time: 11:13 AM
 */

namespace tests\PHPixme\exception;

use \PHPixme\exception\VacuousOffsetException as testTarget;
use PHPixme\Pot;

class VacuousOffsetExceptionTest extends \PHPUnit_Framework_TestCase
{
  public function test_static_creation($value = true)
  {
    $this->assertInstanceOf(testTarget::class, testTarget::of($value));
  }

  public function test_get($value = true)
  {
    $empty = new testTarget($value);
    $this->assertTrue($value, $empty->get());
  }

  public function test_countable($value = true) {
    $empty = new testTarget($value);
    $this->assertEquals(1, $empty->count());
    $this->assertEquals(1, count($empty));
  }

  public function test_toPot($value = true) {
    $empty = new testTarget($value);
    $emptyPot = $empty->toPot();
    $this->assertInstanceOf(Pot::class, $emptyPot);
    $this->assertEquals($empty->get(), $emptyPot->get());
    $this->assertEquals($empty->getMessage(), $emptyPot->getMessage());

  }
}
