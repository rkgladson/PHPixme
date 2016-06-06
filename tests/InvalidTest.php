<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/6/2016
 * Time: 3:19 PM
 */

namespace tests\PHPixme;

use PHPixme\Invalid as testSubject;
use function PHPixme\Invalid as testNew;
use const PHPixme\Invalid as testConst;
use PHPixme\Valid as oppositeSubject;

/**
 * Class InvalidTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Invalid 
 */
class InvalidTest extends \PHPUnit_Framework_TestCase
{
  /** @coversNothing  */
  public function test_constant() {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }
  
}
