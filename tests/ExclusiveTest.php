<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/19/2016
 * Time: 2:52 PM
 */

namespace tests\PHPixme;

use PHPixme as P;

class ExclusiveTest extends \PHPUnit_Framework_TestCase
{
  public function test_constants()
  {
    self::assertEquals(P\Exclusive::class, P\Exclusive);
    self::assertEquals(
      0
      , P\Exclusive::shortName
      , 'the offset of to array should be the head'
    );
  }

  public function test_applicative($value = 1)
  {
    $exclusive = P\Exclusive::of($value);
    self::assertTrue(
      $exclusive->isRight()
      , 'It should have a right hand side preference'
    );
    self::assertInstanceOf(P\Preferred::class, $exclusive);
    self::assertEquals(
      P\Preferred::of($value), $exclusive
      , 'It should be equivalent to the right hand ::of'
    );
  }
}
