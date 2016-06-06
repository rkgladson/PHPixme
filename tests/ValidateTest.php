<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/6/2016
 * Time: 3:08 PM
 */

namespace tests\PHPixme;
use PHPixme\Validate as testSubject;
use function PHPixme\Validate as testNew;
use const PHPixme\Validate as testConst;
/**
 * Class ValidateTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Validate 
 */
class ValidateTest extends \PHPUnit_Framework_TestCase
{
  /** @coversNothing  */
  public function test_constant () {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }
}
