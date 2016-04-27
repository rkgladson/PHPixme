<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/27/2016
 * Time: 9:28 AM
 */

namespace tests\PHPixme;
use PHPixme as P;

class EitherTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants() {
    $this->assertTrue(
      P\Either::class === P\Either
      , 'The constant for the class and the function should be equal'
    );
    $this->assertFalse(
      function_exists(P\Either)
      , 'The companion function should not exist for the class. Either should not have a static applicative.'
    );
  }
  public function test_features() {
    $this->assertNotInstanceOf(
      P\CollectionInterface::class
      , P\Either::class
      , 'Either is not a collection, but a disjoint union representation of a collection.'
    );
  }
}