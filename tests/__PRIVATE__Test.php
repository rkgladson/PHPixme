<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/20/2016
 * Time: 12:53 PM
 */

namespace tests\PHPixme;

use PHPixme\__PRIVATE__ as internal;
use PHPixme\Seq;
use function PHPixme\_ as _;

/**
 * Class __PRIVATE__Tests
 * This is to test some of the critical components that are not part of the public-facing API,
 * but are critical to the correctness of the public API
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\__PRIVATE__
 */
class __PRIVATE__Test extends \PHPUnit_Framework_TestCase
{

  /**
   * @covers PHPixme\__PRIVATE__::getDescriptor
   */
  public function test_getDescriptor()
  {
    self::assertEquals(\stdClass::class, internal::getDescriptor(new \stdClass()));
    $other = [
      [], [1], 0, 1, -0.0, +0.0, 1.0, NAN, INF, '', '^_^', null, true, false
    ];
    array_walk($other, function ($value) {
      self::assertEquals(gettype($value), internal::getDescriptor($value));
    });
  }

  /**
   * @covers PHPixme\__PRIVATE__::getDescriptor
   * @requires extension curl
   */
  public function test_getDescriptor_resource()
  {
    $handle = curl_init('http://www.wikipedia.com/');
    self::assertEquals('curl', internal::getDescriptor($handle));
    curl_close($handle);
  }

  /**
   * @covers ::getArrayFrom
   * @dataProvider getArrayFromProvider
   */
  public function test_getArrayFrom($container, $expected)
  {
    self::assertSame($expected, internal::getArrayFrom($container));
  }

  /**
   * @covers ::copyTransversable
   */
  public function test_copyTransversable()
  {
    $arr = [1, 2, 3];
    $arr[0] = 0;
    $iter = new \ArrayIterator($arr);

    $result = internal::copyTransversable($iter);
    self::assertInstanceOf(\Iterator::class, $result);
    self::assertInstanceOf(\ArrayIterator::class, $result);
    self::assertInstanceOf(get_class($result), $result);
    self::assertTrue($iter == $result);
    self::assertFalse($iter === $result);

    $iterAggr = new \ArrayObject($arr);
    $result = internal::copyTransversable($iterAggr);
    self::assertInstanceOf(\Iterator::class, $result);
    self::assertInstanceOf(
      get_class($iterAggr->getIterator())
      , $result
      , 'it should not clone iterator aggregates, rather just get an instance of a iterator.'
    );
  }

  /**
   * @covers ::copyTransversable
   */
  public function test_copyTransversable_generator()
  {
    $gen = function () {
      yield 1;
    };
    $iterator = $gen();
    self::assertTrue(
      $iterator === internal::copyTransversable($iterator)
      , "Php 5.6 and 7 don't allow for cloning generator iterators, so we should silently fail if the generator is still good."
    );
  }

  /**
   * @covers ::protectTraversable
   */
  public function test_protectTraversable()
  {
    $subjectR = [1, 2, 3];
    $subjectI = new JustAIterator($subjectR);

    $resultI = internal::protectTraversable($subjectI);

    self::assertEquals($resultI, $subjectI);
    self::assertNotSame($resultI, $subjectI);
    self::assertEquals($subjectR, internal::protectTraversable($subjectR));
  }

  /**
   * @covers ::traversableToArray
   * @dataProvider traversableToArrayProvider
   */
  public function test_traversableToArray ($subject, $expected) {
    self::assertEquals($expected, internal::traversableToArray($subject));
  }


  /**
   * @covers PHPixme\__PRIVATE__::curryExactly1
   */
  public function test_curryExactly1()
  {
    $arity1 = internal::curryExactly1(function (...$args) {
      return $args;
    });
    self::assertInstanceOf(
      Closure
      , $arity1
      , 'It should wrap the function in a closure'
    );
    $thunk = $arity1();
    self::assertInstanceOf(
      Closure
      , $thunk
      , 'when executed with no arguments, it should be a thunk'
    );
    self::assertSame(
      $thunk
      , $arity1
      , 'for performance reasons, it should not produce new closures when given no arguments'
    );
    self::assertEquals(
      [1]
      , $arity1(1, 2, 3, 4)
      , 'it should eat all arguments but one'
    );
  }

  /**
   * @covers PHPixme\__PRIVATE__::curryExactly2
   */
  public function test_curryExactly2()
  {
    $arity2 = internal::curryExactly2(function (...$args) {
      return $args;
    });
    self::assertInstanceOf(
      Closure
      , $arity2
      , 'it should wrap into a closure'
    );
    // Testing thunks
    self::assertInstanceOf(
      Closure
      , $arity2()
      , 'thunks should return a closure'
    );
    self::assertSame(
      $arity2
      , $arity2()
      , 'at zero arguments, that closure should be itself'
    );
    self::assertSame(
      $arity2
      , $arity2(_(), _())
      , 'thunks should have no effect'
    );
    self::assertSame(
      $arity2
      , $arity2(_())
      , 'thunks should have no effect'
    );
    // Test eating
    self::assertEquals(
      [1, 2]
      , $arity2(1, 2, 3, 4)
      , 'the function should eat all but two arguments'
    );
    // v1 defined
    self::assertEquals(
      $arity2(1)->__invoke(2, 3, 4, 5)
      , $arity2(1, 2, 6, 7, 8)
    );
    $intermediate = $arity2(1);
    self::assertSame(
      $intermediate
      , $intermediate()
      , 'An intermediate X1 _2 should be a thunk'
    );
    self::assertSame(
      $intermediate
      , $intermediate(_())
      , 'An intermediate X1 _2 should be a thunk'
    );
    // v2 defined
    self::assertEquals(
      $arity2(_(), 2)->__invoke(1, 3, 4, 5)
      , $arity2(1, 2, 6, 7, 8)
    );
    $intermediate = $arity2(_(), 2);
    self::assertSame(
      $intermediate, $intermediate()
      , 'An intermediate X1 _2 should be a thunk'
    );
    self::assertSame(
      $intermediate, $intermediate(_())
      , 'An intermediate X1 _2 should be a thunk'
    );
  }

  /**
   * @covers PHPixme\__PRIVATE__::curryExactly3
   */
  public function test_curryExactly3()
  {
    // "The flow of time itself is convoluted; with heroes centuries old phasing in and out." -- Solaire of Astora
    $arity3 = internal::curryExactly3(function (...$args) {
      return $args;
    });
    $expectedOutput = [1, 2, 3];
    // Establishing some basic qualities of curryExactly3
    self::assertInstanceOf(Closure, $arity3);
    /*
     * A note about the notation.
     * => means imply.
     * (x)->a is a function.
     * X1 means that the first argument is set
     *  _1 means it is explicitly void in a call
     * -> ... -> between argument states means any number of identity operations caused by no arguments
     * ≅ means 'is essentially the same as' or more formally the categorically congruent to.
     * ∴ means 'therefore'
     * ∎ means end of a proof or Q.E.D.
     * In variable naming __ means a step in the sequence
     */

    /** Show that curryExactly3((x y z) -> a) ≅ (x y z) -> a **/
    self::assertInstanceOf(Closure, $arity3());
    self::assertSame($arity3, $arity3());
    self::assertSame($arity3, $arity3(_()));
    self::assertSame($arity3, $arity3(_(), _()));
    self::assertSame($arity3, $arity3(_(), _(), _()));
    // => curryExactly3((x y z)->a) -> ... -> a
    self::assertEquals($expectedOutput, $arity3(1, 2, 3, 4, 5));
    /** ∴ curryExactly3((x y z) -> a) ≅ (x y z) -> a ∎ **/

    /** show that  (X1)->(X2)->(X3)  ≅ (X1 X2 X3) **/
    $step1 = $arity3(1);
    // Prove that step 1 is a thunk
    self::assertInstanceOf(Closure, $step1);
    self::assertSame($step1, $step1());
    self::assertSame($step1, $step1(_()));
    self::assertSame($step1, $step1(_(), _()));
    $step2 = $step1(2);
    // Prove that step2 is a thunk
    self::assertInstanceOf(Closure, $step2);
    self::assertSame($step2, $step2());
    self::assertSame($step2, $step2(_()));
    self::assertSame($step2, $step2(_(), _()));

    self::assertEquals($expectedOutput, $step2(3, 4, 5));
    // => (X1)->...-> (X2) -> ...-> (X3) -> a
    unset($step1, $step2);
    /** ∴ (X1)->(X2)->(X3) ≅ (X1 X2 X3) ∎ **/


    /** Show that (X1 X2 X3)
     * ≅ (X1) -> (X2 X3)
     * ≅ (X1 _2)-> (X2 X3)
     * ≅ (X1 _2 _3) -> (X2 X3)
     **/
    // The step above proves that (X1) is a thunk.
    $step1X1_2 = $arity3(1, _());
    $step1X1_2_3 = $arity3(1, _(), _());
    // (X1) properties were proven previously as a thunk and a closure
    self::assertInstanceOf(Closure, $step1X1_2);
    self::assertInstanceOf(Closure, $step1X1_2_3);
    // (X1 _2) is a thunk
    self::assertSame($step1X1_2, $step1X1_2());
    self::assertSame($step1X1_2, $step1X1_2(_()));
    self::assertSame($step1X1_2, $step1X1_2(_(), _()));
    // (X1 _2 _3) is a thunk
    self::assertSame($step1X1_2_3, $step1X1_2_3());
    self::assertSame($step1X1_2_3, $step1X1_2_3(_()));
    self::assertSame($step1X1_2_3, $step1X1_2_3(_(), _()));
    // => (X1)-> ... -> (X2 X3) ≅ (X1 _2)-> ... -> (X2 X3) ≅ (X1 _2 _3)-> ... -> (X2 X3)
    self::assertEquals($expectedOutput, $arity3(1)->__invoke(2, 3, 7));
    self::assertEquals($expectedOutput, $step1X1_2(2, 3, 7));
    self::assertEquals($expectedOutput, $step1X1_2_3(2, 3, 7));
    unset($step1X1, $step1X1_2, $step1X1_2_3);
    /** ∴ (X1)->(X2 X3) ≅ (X1 _2)-> (X2 X3) ≅  (X1 _2 _3) -> (X2 X3) ≅ (X1 X2 X3) ∎ **/

    /** Show that (X1 X2 X3)
     * ≅ (X1 X2) -> (X3)
     * ≅ (X1 X2 _3)-> (X3)
     **/
    $step1X1X2 = $arity3(1, 2);
    $step1X1X2_3 = $arity3(1, 2, _());
    self::assertInstanceOf(Closure, $step1X1X2);
    self::assertSame($step1X1X2, $step1X1X2());
    self::assertSame($step1X1X2, $step1X1X2(_()));
    // (X1 X2) is a thunk
    self::assertInstanceOf(Closure, $step1X1X2_3);
    self::assertSame($step1X1X2_3, $step1X1X2_3());
    self::assertSame($step1X1X2_3, $step1X1X2_3(_()));
    // (X1 X2 _3) is a thunk
    // => (X1 X2 _3) -> ... -> (X3) ≅ (X1 X2) -> ... -> (X3)
    self::assertEquals($expectedOutput, $step1X1X2(3, 4, 5));
    self::assertEquals($expectedOutput, $step1X1X2_3(3, 2));
    unset ($step1X1X2, $step1X1X2_3);
    /** ∴ (X1 X2 X3)
     * ≅ (X1 X2) -> (X3)
     * ≅ (X1 X2 _3) -> (X3) ∎
     **/

    /** Show that (X1 X2 X3)
     * ≅ (_1 _2 X3) -> (X1 X2)
     * ≅ (_1 _2 X3) -> (X1) -> (X2)
     * ≅ (_1 _2 X3) -> (X1 _2) -> (X2)
     **/
    $step1_1_2X3 = $arity3(_(), _(), 3);
    self::assertInstanceOf(Closure, $step1_1_2X3);
    self::assertSame($step1_1_2X3, $step1_1_2X3());
    self::assertSame($step1_1_2X3, $step1_1_2X3(_()));
    self::assertSame($step1_1_2X3, $step1_1_2X3(_(), _()));
    // (_1 _2 X3) is a thunk
    $step2_1_2X3__X1 = $step1_1_2X3(1);
    $step2_1_2X3__X1_3 = $step1_1_2X3(1, _());

    self::assertInstanceOf(Closure, $step2_1_2X3__X1);
    self::assertSame($step2_1_2X3__X1, $step2_1_2X3__X1());
    self::assertSame($step2_1_2X3__X1, $step2_1_2X3__X1(_()));
    // (_1 _2 X3) -> (X1) is a thunk

    self::assertInstanceOf(Closure, $step2_1_2X3__X1_3);
    self::assertSame($step2_1_2X3__X1_3, $step2_1_2X3__X1_3());
    self::assertSame($step2_1_2X3__X1_3, $step2_1_2X3__X1_3(_()));
    // (_1 _2 X3) -> (X1 _2) is a thunk

    // => (_1 _2 X3) -> ... -> (X1 X2)
    // ≅ (_1 _2 X3) -> ... -> (X1) -> ... -> (X2)
    // ≅ (_1 _2 X3) -> ... -> (X1 _2) -> ... -> (X2)
    self::assertEquals($expectedOutput, $step1_1_2X3(1, 2, 4));
    self::assertEquals($expectedOutput, $step2_1_2X3__X1(2, 4));
    self::assertEquals($expectedOutput, $step2_1_2X3__X1_3(2, 4));
    unset($step1_1_2X3, $step2_1_2X3__X1, $step2_1_2X3__X1_3);
    /** ∴ (X1 X2 X3)
     * ≅ (_1 _2 X3) -> (X1 X2)
     * ≅ (_1 _2 X3) -> (X1) -> (X2)
     * ≅ (_1 _2 X3) -> (X1 _2) -> (X2) ∎
     **/


    /** Show (X1 X2 X3)
     * ≅ (_1 X2) -> (X1 X3)
     * ≅ (_1 X2) -> (X1) -> (X3)
     * ≅ (_1 X2) -> (X1 _3) -> (X3)
     * ≅ (_1 X2 _3) -> (X1 X3)
     * ≅ (_1 X2 _3) -> (X1) -> (X3)
     * ≅ (_1 X2 _3) -> (X1 _3) -> (X3)
     **/
    $step1_1X2 = $arity3(_(), 2);
    self::assertInstanceOf(Closure, $step1_1X2);
    self::assertSame($step1_1X2, $step1_1X2());
    self::assertSame($step1_1X2, $step1_1X2(_()));
    self::assertSame($step1_1X2, $step1_1X2(_(), _()));
    // (_1 X2) is a thunk

    $step1_1X2_3 = $arity3(_(), 2, _());
    self::assertInstanceOf(Closure, $step1_1X2_3);
    self::assertSame($step1_1X2_3, $step1_1X2_3());
    self::assertSame($step1_1X2_3, $step1_1X2_3(_()));
    self::assertSame($step1_1X2_3, $step1_1X2_3(_(), _()));
    // (_1 X2 _3) is a thunk

    $step2_1X2__X1 = $step1_1X2(1);
    self::assertInstanceOf(Closure, $step2_1X2__X1);
    self::assertSame($step2_1X2__X1, $step2_1X2__X1());
    self::assertSame($step2_1X2__X1, $step2_1X2__X1(_()));
    // (_1 X2) -> (X1) is a thunk

    $step2_1X2_3__X1 = $step1_1X2_3(1);
    self::assertInstanceOf(Closure, $step2_1X2_3__X1);
    self::assertSame($step2_1X2_3__X1, $step2_1X2_3__X1());
    self::assertSame($step2_1X2_3__X1, $step2_1X2_3__X1(_()));
    // (_1 X2 _3) -> (X1) is a thunk

    $step2_1X2__X1_3 = $step1_1X2(1, _());
    self::assertInstanceOf(Closure, $step2_1X2__X1_3);
    self::assertSame($step2_1X2__X1_3, $step2_1X2__X1_3());
    self::assertSame($step2_1X2__X1_3, $step2_1X2__X1_3(_()));
    // (_1 X2) -> (X1 _3) is a thunk

    $step2_1X2_3__X1_3 = $step1_1X2_3(1, _());
    self::assertInstanceOf(Closure, $step2_1X2_3__X1_3);
    self::assertSame($step2_1X2_3__X1_3, $step2_1X2_3__X1_3());
    self::assertSame($step2_1X2_3__X1_3, $step2_1X2_3__X1_3(_()));
    // (_1 X2 _3) -> (X1 _3) is a thunk

    // => (_1 X2) -> ... -> (X1 X3)
    // ≅ (_1 X2 _3) -> ... -> (X1 X3)
    // ≅ (_1 X2) -> ... -> (X1) -> ... -> (X3)
    // ≅ (_1 X2 _3) ->  ... ->(X1) -> ... -> (X3)
    // ≅ (_1 X2) -> ... -> (X1 _3) -> ... -> (X3)
    // ≅ (_1 X2 _3) -> ... -> (X1 _3) -> ... -> (X3)
    self::assertEquals($expectedOutput, $step1_1X2(1, 3, 7));
    self::assertEquals($expectedOutput, $step1_1X2_3(1, 3, 7));
    self::assertEquals($expectedOutput, $step2_1X2__X1(3, 7));
    self::assertEquals($expectedOutput, $step2_1X2_3__X1(3, 7));
    self::assertEquals($expectedOutput, $step2_1X2__X1_3(3, 7));
    self::assertEquals($expectedOutput, $step2_1X2_3__X1_3(3, 7));

    unset($step1_1X2, $step1_1X2_3, $step2_1X2__X1, $step2_1X2_3__X1, $step2_1X2__X1_3, $step2_1X2_3__X1_3);
    /** ∴  (X1 X2 X3)
     * ≅ (_1 X2) -> (X1 X3)
     * ≅ (_1 X2) -> (X1) -> (X3)
     * ≅ (_1 X2) -> (X1 _3) -> (X3)
     * ≅ (_1 X2 _3) -> (X1 X3)
     * ≅ (_1 X2 _3) -> (X1) -> (X3)
     * ≅ (_1 X2 _3) -> (X1 _3) -> (X3) ∎
     **/


    /** Show that (X1 X2 X3)
     * ≅ (X1) -> (_2 X3) -> (X2)
     * ≅ (X1 _2) -> (_2 X3) -> (X2)
     * ≅ (X1 _2 _3) -> (_2 X3) -> (X2)
     * ≅ (X1 _2 X3) -> (X2)
     */
    // Previously proven: (X1) === (X1 _2) === (X1 _2 _3) Skipping step.
    $step2X1___2X3 = $arity3(1)->__invoke(_(), 3);
    self::assertInstanceOf(Closure, $step2X1___2X3);
    self::assertSame($step2X1___2X3, $step2X1___2X3());
    self::assertSame($step2X1___2X3, $step2X1___2X3(_()));
    // (X1) -> (_2 X3) is a thunk

    $step1X1_2X3 = $arity3(1, _(), 3);
    self::assertInstanceOf(Closure, $step1X1_2X3);
    self::assertSame($step1X1_2X3, $step1X1_2X3());
    self::assertSame($step1X1_2X3, $step1X1_2X3(_()));
    // (X1 _2 X3) is a thunk

    // => (X1)-> ... -> (_2 X3) -> ... -> (X2)
    // ≅ (X1 _2 X3) -> ... -> (X2)
    self::assertEquals($expectedOutput, $step2X1___2X3(2, 7));
    self::assertEquals($expectedOutput, $step1X1_2X3(2, 7));
    unset ($step2X1___2X3, $step1X1_2X3);
    /** ∴ (X1 X2 X3)
     * ≅ (X1) -> (_2 X3) -> (X2)
     * ≅ (X1 _2) -> (_2 X3) -> (X2)
     * ≅ (X1 _2 _3) -> (_2 X3) -> (X2)
     * ≅ (X1 _2 X3) -> (X2) ∎
     */

    /** Show that (X1 X2 X3)
     * ≅ (_1 X2 X3) -> (X1)
     * ≅ (_1 X2)->(_1 X3) -> (X1)
     * ≅ (_1 X2 _3) -> (_1 X3) -> (X1)
     **/
    $step1_1X2X3 = $arity3(_(), 2, 3);
    self::assertInstanceOf(Closure, $step1_1X2X3);
    self::assertSame($step1_1X2X3, $step1_1X2X3());
    self::assertSame($step1_1X2X3, $step1_1X2X3(_()));
    // (_1 X2 X3) is a thunk

    // Previously we showed that (_1 X2) === (_1 X2 _3) and showed they were a thunk.
    // Skipping proofs for both of these steps

    $step2_1X2___1X3 = $arity3(_(), 2)->__invoke(_(), 3);
    self::assertInstanceOf(Closure, $step2_1X2___1X3);
    self::assertSame($step2_1X2___1X3, $step2_1X2___1X3());
    self::assertSame($step2_1X2___1X3, $step2_1X2___1X3(_()));
    // (_1 X2) -> (_1 X3) is a thunk

    $step2_1X2_3___1X3 = $arity3(_(), 2)->__invoke(_(), 3);
    self::assertInstanceOf(Closure, $step2_1X2_3___1X3);
    self::assertSame($step2_1X2_3___1X3, $step2_1X2_3___1X3());
    self::assertSame($step2_1X2_3___1X3, $step2_1X2_3___1X3(_()));
    // (_1 X2 _3) -> (_1 X3) is a thunk

    // => (_1 X2 X3) -> ... -> (X1)
    // ≅ (_1 X2)-> ... -> (_1 X3) -> ... -> (X1)
    // ≅ (_1 X2 _3) -> ... -> (_1 X3) -> ... ->(X1)
    self::assertEquals($expectedOutput, $step1_1X2X3(1, 7));
    self::assertEquals($expectedOutput, $step2_1X2___1X3(1, 7));
    self::assertEquals($expectedOutput, $step2_1X2_3___1X3(1, 7));
    unset($step1_1X2X3, $step2_1X2___1X3, $step2_1X2_3___1X3);
    /** ∴ (X1 X2 X3)
     * ≅ (_1 X2 X3) -> (X1)
     * ≅ (_1 X2)->(_1 X3) -> (X1)
     * ≅ (_1 X2 _3) -> (_1 X3) -> (X1) ∎
     **/


    /** Show that (X1 X2 X3) ≅ (_1 _2 X3) -> (_1 X2) -> (X1) **/
    // Previously we proved that (_1 _2 X3) is a thunk.
    // Skipping proof for this step
    $step2_1_2X3___1X2 = $arity3(_(), _(), 3)->__invoke(_(), 2);
    self::assertInstanceOf(Closure, $step2_1_2X3___1X2);
    self::assertSame($step2_1_2X3___1X2, $step2_1_2X3___1X2());
    self::assertSame($step2_1_2X3___1X2, $step2_1_2X3___1X2(_()));
    // (_1 _2 X3) -> (_1 X2) is a thunk

    // => (_1 _2 X3) -> (_1 X2) -> (X1)
    self::assertEquals($expectedOutput, $step2_1_2X3___1X2(1, 7));
    unset ($step2_1_2X3___1X2);
    /** ∴ (X1 X2 X3) ≅ (_1 _2 X3) -> (_1 X2) -> (X1) ∎ **/


    // Praise the sun! \o/
    // Let us now go forth and engage in jolly cooperation!
  }

  /**
   * @covers ::reflectCallable
   * @dataProvider callableProvider
   */
  public function test_reflectCallable(callable $callable, $descriptor)
  {
    $reflection = internal::reflectCallable($callable);
    self::assertInstanceOf(\ReflectionFunctionAbstract::class, $reflection);
    self::assertInstanceOf($descriptor['reflection'], $reflection);
  }

  /**
   * @covers ::getArity
   * @dataProvider callableProvider
   */
  public function test_getArity(callable $callable, $descriptor)
  {
    self::assertEquals($descriptor['arity'], internal::getArity($callable));
  }

  /**
   * @coversNothing
   * @dataProvider internalInstanceProvider
   * @param string $key
   */
  public function test_instanceFunctions($key)
  {
    // This is a canary for something horribly mismatched.
    $closure = internal::$instance[$key];
    self::assertInstanceOf(Closure, $closure);
    self::assertInstanceOf(Closure, $closure(), 'the thunk should return a closure');
    self::assertSame($closure, $key(), 'Thunk currying should return the value stored in internal');
  }

  public function callableProvider()
  {
    $object = new TestClass();
    $closure = function ($x) {
      return $x;
    };
    return [
      'static function' => [
        \PHPixme\I
        , [
          'arity' => 1
          , 'reflection' => \ReflectionFunction::class
        ]
      ]
      , 'closures' => [
        $closure,
        [
          'arity' => 1
          , 'reflection' => \ReflectionFunction::class
        ]
      ]
      , 'static method string' => [
        TestClass::class . '::testStatic'
        , [
          'arity' => 0
          , 'reflection' => \ReflectionMethod::class
        ]
      ]
      , 'array-class static method' => [
        [TestClass::class, 'testStatic']
        , [
          'arity' => 0
          , 'reflection' => \ReflectionMethod::class
        ]
      ]
      , 'array-object method' => [
        [$object, 'getArgs']
        , [
          'arity' => 0
          , 'reflection' => \ReflectionMethod::class
        ]
      ]
      , 'array-object static method' => [
        [$object, 'testStatic']
        , [
          'arity' => 0
          , 'reflection' => \ReflectionMethod::class
        ]
      ]
      , 'invokable objects' => [
        new invokable(), [
          'arity' => 1
          , 'reflection' => \ReflectionMethod::class
        ]
      ]
    ];
  }

  public function internalInstanceProvider()
  {
    // Flatten out the internal instance so that it can be easily identified where a value might be pointing elsewhere
    $output = call_user_func_array('array_merge', array_map(function ($key) {
      return [$key => [$key]];
    }, array_filter(array_keys(internal::$instance), function ($key) {
      return strpos($key, 'PHPixme\\') === 0;
    })));
    // LEAVE LIKE THIS FOR DEBUGGING
    return $output;
  }

  public function wellKnownContainers()
  {
    $empty = [];
    $filled = [1, 2, 3];
    return [
      'empty array' => [$empty, $empty]
      , 'empty Seq' => [new Seq([]), $empty]
      , 'empty ArrayObject' => [new \ArrayObject($empty), $empty]
      , 'empty ArrayIterator' => [new \ArrayIterator($empty), $empty]
      , 'empty SplFixedArray' => [\SplFixedArray::fromArray($empty), $empty]
      , 'array' => [$filled, $filled]
      , 'Seq' => [new Seq($filled), $filled]
      , 'ArrayObject' => [new \ArrayObject($filled), $filled]
      , 'ArrayIterator' => [new \ArrayIterator($filled), $filled]
      , 'SplFixedArray' => [\SplFixedArray::fromArray($filled), $filled]
    ];

  }

  public function getArrayFromProvider()
  {
    return array_merge(
      $this->wellKnownContainers()
      , [
        'not well known' => [new \stdClass(), null]
      ]
    );
  }

  public function traversableToArrayProvider()
  {
    $filled = [1,2,3];
    return array_merge(
      $this->wellKnownContainers()
      , [
        'EmptyIterator' => [new \EmptyIterator(), []]
        , 'JustAnIterator' => [new JustAIterator($filled), $filled]
      ]
    );
  }
}



