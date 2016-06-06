<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/27/2016
 * Time: 3:22 PM
 */

namespace tests\PHPixme;

/**
 * Class AssertTypeTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\AssertTypeTrait
 */
class AssertTypeTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers ::assertType
   */
  public function test_success_contract()
  {
    $val = new AssertTypeStub();
    $this->assertSame($val, AssertTypeStub::assertType($val));
  }

  /**
   * @dataProvider brokenContractProvider
   * @covers ::assertType
   */
  public function test_failure_contract($notType)
  {
    $this->expectException(\UnexpectedValueException::class);
    AssertTypeStub::assertType($notType);
  }

  public function brokenContractProvider() {
    return [
      [[]]
      , [true]
      , [null]
      , [new \stdClass()]
      , ['']
      , [1]
      , [1.0]
    ];
  }
}