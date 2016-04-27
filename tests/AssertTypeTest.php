<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/27/2016
 * Time: 3:22 PM
 */

namespace tests\PHPixme;
use PHPixme as P;

class AssertTypeTest extends \PHPUnit_Framework_TestCase
{
  public function test_success_contract()
  {
    $val = new AssertTypeStub();
    $this->assertTrue($val === AssertTypeStub::assertType($val));
  }

  /**
   * @dataProvider brokenContractProvider
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
      , [P\Some(1)]
      , ['']
      , [1]
      , [1.0]
    ];
  }
}