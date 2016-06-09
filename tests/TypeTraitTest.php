<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/27/2016
 * Time: 3:22 PM
 */

namespace tests\PHPixme;
use PHPixme\exception\InvalidReturnException as invalidReturn;
/**
 * Class AssertTypeTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\TypeTrait
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers ::assertType
   */
  public function test_success_contract()
  {
    $val = new TypeStub();
    $this->assertSame($val, TypeStub::assertType($val));
  }

  /**
   * @dataProvider brokenContractProvider
   * @covers ::assertType
   */
  public function test_failure_contract($notType)
  {
    $this->expectException(invalidReturn::class);
    TypeStub::assertType($notType);
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