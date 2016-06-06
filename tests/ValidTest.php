<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/6/2016
 * Time: 3:20 PM
 */

namespace tests\PHPixme;
use PHPixme\Valid as testSubject;
use function PHPixme\Valid as testNew;
use const PHPixme\Valid as testConst;
use PHPixme\Invalid as oppositeSubject;

/**
 * Class ValidTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Valid
 */
class ValidTest extends \PHPUnit_Framework_TestCase
{
  /** @coversNothing */
  public function test_constants() {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  } 
}
