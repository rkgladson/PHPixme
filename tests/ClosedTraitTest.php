<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/6/2016
 * Time: 4:48 PM
 */

namespace tests\PHPixme;


use PHPixme\ClosedTrait;
use PHPixme\exception\MutationException;

/**
 * Class ClosedTraitTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\ClosedTrait
 */
class ClosedTraitTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  function test_get_set_final() {
    $reflection = new \ReflectionClass(ClosedTrait::class);
    $this->assertTrue($reflection->getMethod('__get')->isFinal());
    $this->assertTrue($reflection->getMethod('__set')->isFinal());
  }

  /**
   * @covers ::__get
   */
  function test_exception_on_get_dynamic() {
    $this->expectException(MutationException::class);
    $obj = new CloseTypeStub();
    $obj->exceptionFoo;
  }

  /**
   * @covers ::__set
   */
  function test_exception_on_set_dynamic() {
    $this->expectException(MutationException::class);
    $obj = new CloseTypeStub();
    $obj->exceptionFoo = 'bar';
  }
}
