<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/8/2016
 * Time: 12:46 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Maybe as testSubject;
use function PHPixme\Maybe as testNew;
use const PHPixme\Maybe as testConst;
use PHPixme\Some;
use PHPixme\None;

class MaybeTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @coversNothing 
   */
  public function test_Maybe_constants()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }
  
  /**
   * @covers PHPixme\Maybe
   * @dataProvider maybeEmptyProvider
   */
  public function test_Maybe_companion_none_result($value)
  {
    self::assertInstanceOf(None::class, testNew($value));
  }

  /**
   * @covers PHPixme\Maybe
   * @dataProvider maybeSomethingProvider
   */
  public function test_Maybe_companion_some_result($value)
  {
    self::assertInstanceOf(Some::class, P\Maybe($value));
  }

  /**
   * @dataProvider maybeEmptyProvider
   * @covers PHPixme\Maybe::of
   */
  public function test_static_of_nothing($value)
  {
    self::assertInstanceOf(None::class, testSubject::of($value));
  }

  /**
   * @dataProvider maybeSomethingProvider
   * @covers PHPixme\Maybe::of
   */
  public function test_static_of_something($value)
  {
    self::assertInstanceOf(Some::class, testSubject::of($value));
  }

  public function maybeEmptyProvider()
  {
    return [
      [null]
    ];
  }

  public function maybeSomethingProvider()
  {
    return [
      [0]
      , [[]]
      , ['']
      , [P\None()]
      , [false]
      , [true]
      , [1]
      , [1.1]
      , ['1']
      , [[1]]
      , [new \stdClass()]
      , [P\Some('')]
    ];
  }
}
