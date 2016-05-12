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
      function_exists(P\Either::class)
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

  /**
   * @dataProvider reflectionProvider
   * @param \ReflectionClass $reflection
   */
  public function test_not_collection (\ReflectionClass $reflection ) {
    $this->assertFalse(
      $reflection->implementsInterface(P\CollectionInterface::class)
      , 'Either, while having some collection like qualities, is an exclusive disjunction, not a collection.'
      . ' Collections only have one path of operation, while Either has two possible states of paths.'
    );
  }

  /**
   * @dataProvider reflectionProvider
   * @param \ReflectionClass $reflection
   */
  public function test_no_of(\ReflectionClass $reflection ) {

    $this->assertTrue(
      $reflection->implementsInterface(P\SingleStaticCreation::class)
    );
    $this->assertTrue(
      $reflection->getMethod('of')->isAbstract()
      , 'Either should not implement of, as it is not a collection of its own, but a disjunction'
    );
  }

  public function reflectionProvider () {
    return [[new \ReflectionClass(P\Either::class)]];
  }
}