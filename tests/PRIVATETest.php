<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/20/2016
 * Time: 12:53 PM
 */

namespace tests\PHPixme;

use PHPixme\__PRIVATE__ as internal;
use PHPixme\__PRIVATE__;
use PHPixme\CollectionInterface;
use function PHPixme\_ as _;
const PHPixme = 'PHPixme';
/**
 * Class __PRIVATE__Tests
 * This is to test some of the critical components that are not part of the public-facing API,
 * but are critical to the correctness of the public API
 * @package tests\PHPixme
 */
class PRIVATETest extends \PHPUnit_Framework_TestCase
{

  public function test_curryExactly1()
  {
    $arity1 = internal::curryExactly1(function (...$args) {
      return $args;
    });
    $this->assertInstanceOf(
      Closure
      , $arity1
      , 'It should wrap the function in a closure'
    );
    $thunk = $arity1();
    $this->assertInstanceOf(
      Closure
      , $thunk
      , 'when executed with no arguments, it should be a thunk'
    );
    $this->assertTrue(
      $thunk === $arity1
      , 'for performance reasons, it should not produce new closures when given no arguments'
    );
    $this->assertEquals(
      [1]
      , $arity1(1, 2, 3, 4)
      , 'it should eat all arguments but one'
    );
  }

  public function test_curryExactly2()
  {
    $arity2 = internal::curryExactly2(function (...$args) {
      return $args;
    });
    $this->assertInstanceOf(
      Closure
      , $arity2
      , 'it should wrap into a closure'
    );
    // Testing thunks
    $this->assertInstanceOf(
      Closure
      , $arity2()
      , 'thunks should return a closure'
    );
    $this->assertTrue(
      $arity2() === $arity2
      , 'at zero arguments, that closure should be itself'
    );
    $this->assertTrue(
      $arity2() === $arity2(_(), _())
      , 'thunks should have no effect'
    );
    $this->assertTrue(
      $arity2() === $arity2(_())
      , 'thunks should have no effect'
    );
    // Test eating
    $this->assertEquals(
      [1, 2]
      , $arity2(1, 2, 3, 4)
      , 'the function should eat all but two arguments'
    );
    // v1 defined
    $this->assertEquals(
      $arity2(1)->__invoke(2, 3, 4, 5)
      , $arity2(1, 2, 6, 7, 8)
    );
    $intermediate = $arity2(1);
    $this->assertTrue(
      $intermediate === $intermediate()
      , 'An intermediate X1 _2 should be a thunk'
    );
    // v2 defined
    $this->assertEquals(
      $arity2(_(), 2)->__invoke(1, 3, 4, 5)
      , $arity2(1, 2, 6, 7, 8)
    );
    $intermediate = $arity2(_(), 2);
    $this->assertTrue(
      $intermediate === $intermediate()
      , 'An intermediate X1 _2 should be a thunk'
    );
  }

  public function test_curryExactly3()
  {
    // "The flow of time itself is convoluted; with heroes centuries old phasing in and out." -- Solaire of Astora
    $arity3 = internal::curryExactly3(function (...$args) {
      return $args;
    });
    $expectedOutput = [1, 2, 3];
    // Establishing some basic qualities of curryExactly3
    $this->assertInstanceOf(Closure, $arity3);
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
    $this->assertInstanceOf(Closure, $arity3());
    $this->assertTrue($arity3 === $arity3());
    $this->assertTrue($arity3 === $arity3(_()));
    $this->assertTrue($arity3 === $arity3(_(), _()));
    $this->assertTrue($arity3 === $arity3(_(), _(), _()));
    // => curryExactly3((x y z)->a) -> ... -> a
    $this->assertEquals($expectedOutput, $arity3(1, 2, 3, 4, 5));
    /** ∴ curryExactly3((x y z) -> a) ≅ (x y z) -> a ∎ **/

    /** show that  (X1)->(X2)->(X3)  ≅ (X1 X2 X3) **/
    $step1 = $arity3(1);
    // Prove that step 1 is a thunk
    $this->assertInstanceOf(Closure, $step1);
    $this->assertTrue($step1 === $step1());
    $this->assertTrue($step1 === $step1(_()));
    $this->assertTrue($step1 === $step1(_(), _()));
    $step2 = $step1(2);
    // Prove that step2 is a thunk
    $this->assertInstanceOf(Closure, $step2);
    $this->assertTrue($step2 === $step2());
    $this->assertTrue($step2 === $step2(_()));
    $this->assertTrue($step2 === $step2(_(), _()));

    $this->assertEquals($expectedOutput, $step2(3, 4, 5));
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
    $this->assertInstanceOf(Closure, $step1X1_2);
    $this->assertInstanceOf(Closure, $step1X1_2_3);
    // (X1 _2) is a thunk
    $this->assertTrue($step1X1_2 === $step1X1_2());
    $this->assertTrue($step1X1_2 === $step1X1_2(_()));
    $this->assertTrue($step1X1_2 === $step1X1_2(_(), _()));
    // (X1 _2 _3) is a thunk
    $this->assertTrue($step1X1_2_3 === $step1X1_2_3());
    $this->assertTrue($step1X1_2_3 === $step1X1_2_3(_()));
    $this->assertTrue($step1X1_2_3 === $step1X1_2_3(_(), _()));
    // => (X1)-> ... -> (X2 X3) ≅ (X1 _2)-> ... -> (X2 X3) ≅ (X1 _2 _3)-> ... -> (X2 X3)
    $this->assertEquals($expectedOutput, $arity3(1)->__invoke(2, 3, 7));
    $this->assertEquals($expectedOutput, $step1X1_2(2, 3, 7));
    $this->assertEquals($expectedOutput, $step1X1_2_3(2, 3, 7));
    unset($step1X1, $step1X1_2, $step1X1_2_3);
    /** ∴ (X1)->(X2 X3) ≅ (X1 _2)-> (X2 X3) ≅  (X1 _2 _3) -> (X2 X3) ≅ (X1 X2 X3) ∎ **/

    /** Show that (X1 X2 X3)
     * ≅ (X1 X2) -> (X3)
     * ≅ (X1 X2 _3)-> (X3)
     **/
    $step1X1X2 = $arity3(1, 2);
    $step1X1X2_3 = $arity3(1, 2, _());
    $this->assertInstanceOf(Closure, $step1X1X2);
    $this->assertTrue($step1X1X2 === $step1X1X2());
    $this->assertTrue($step1X1X2 === $step1X1X2(_()));
    // (X1 X2) is a thunk
    $this->assertInstanceOf(Closure, $step1X1X2_3);
    $this->assertTrue($step1X1X2_3 === $step1X1X2_3());
    $this->assertTrue($step1X1X2_3 === $step1X1X2_3(_()));
    // (X1 X2 _3) is a thunk
    // => (X1 X2 _3) -> ... -> (X3) ≅ (X1 X2) -> ... -> (X3)
    $this->assertEquals($expectedOutput, $step1X1X2(3, 4, 5));
    $this->assertEquals($expectedOutput, $step1X1X2_3(3, 2));
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
    $this->assertInstanceOf(Closure, $step1_1_2X3);
    $this->assertTrue($step1_1_2X3 === $step1_1_2X3());
    $this->assertTrue($step1_1_2X3 === $step1_1_2X3(_()));
    $this->assertTrue($step1_1_2X3 === $step1_1_2X3(_(), _()));
    // (_1 _2 X3) is a thunk
    $step2_1_2X3__X1 = $step1_1_2X3(1);
    $step2_1_2X3__X1_3 = $step1_1_2X3(1, _());

    $this->assertInstanceOf(Closure, $step2_1_2X3__X1);
    $this->assertTrue($step2_1_2X3__X1 === $step2_1_2X3__X1());
    $this->assertTrue($step2_1_2X3__X1 === $step2_1_2X3__X1(_()));
    // (_1 _2 X3) -> (X1) is a thunk

    $this->assertInstanceOf(Closure, $step2_1_2X3__X1_3);
    $this->assertTrue($step2_1_2X3__X1_3 === $step2_1_2X3__X1_3());
    $this->assertTrue($step2_1_2X3__X1_3 === $step2_1_2X3__X1_3(_()));
    // (_1 _2 X3) -> (X1 _2) is a thunk

    // => (_1 _2 X3) -> ... -> (X1 X2)
    // ≅ (_1 _2 X3) -> ... -> (X1) -> ... -> (X2)
    // ≅ (_1 _2 X3) -> ... -> (X1 _2) -> ... -> (X2)
    $this->assertEquals($expectedOutput, $step1_1_2X3(1, 2, 4));
    $this->assertEquals($expectedOutput, $step2_1_2X3__X1(2, 4));
    $this->assertEquals($expectedOutput, $step2_1_2X3__X1_3(2, 4));
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
    $this->assertInstanceOf(Closure, $step1_1X2);
    $this->assertTrue($step1_1X2 === $step1_1X2());
    $this->assertTrue($step1_1X2 === $step1_1X2(_()));
    $this->assertTrue($step1_1X2 === $step1_1X2(_(), _()));
    // (_1 X2) is a thunk

    $step1_1X2_3 = $arity3(_(), 2, _());
    $this->assertInstanceOf(Closure, $step1_1X2_3);
    $this->assertTrue($step1_1X2_3 === $step1_1X2_3());
    $this->assertTrue($step1_1X2_3 === $step1_1X2_3(_()));
    $this->assertTrue($step1_1X2_3 === $step1_1X2_3(_(), _()));
    // (_1 X2 _3) is a thunk

    $step2_1X2__X1 = $step1_1X2(1);
    $this->assertInstanceOf(Closure, $step2_1X2__X1);
    $this->assertTrue($step2_1X2__X1 === $step2_1X2__X1());
    $this->assertTrue($step2_1X2__X1 === $step2_1X2__X1(_()));
    // (_1 X2) -> (X1) is a thunk

    $step2_1X2_3__X1 = $step1_1X2_3(1);
    $this->assertInstanceOf(Closure, $step2_1X2_3__X1);
    $this->assertTrue($step2_1X2_3__X1 === $step2_1X2_3__X1());
    $this->assertTrue($step2_1X2_3__X1 === $step2_1X2_3__X1(_()));
    // (_1 X2 _3) -> (X1) is a thunk

    $step2_1X2__X1_3 = $step1_1X2(1, _());
    $this->assertInstanceOf(Closure, $step2_1X2__X1_3);
    $this->assertTrue($step2_1X2__X1_3 === $step2_1X2__X1_3());
    $this->assertTrue($step2_1X2__X1_3 === $step2_1X2__X1_3(_()));
    // (_1 X2) -> (X1 _3) is a thunk

    $step2_1X2_3__X1_3 = $step1_1X2_3(1, _());
    $this->assertInstanceOf(Closure, $step2_1X2_3__X1_3);
    $this->assertTrue($step2_1X2_3__X1_3 === $step2_1X2_3__X1_3());
    $this->assertTrue($step2_1X2_3__X1_3 === $step2_1X2_3__X1_3(_()));
    // (_1 X2 _3) -> (X1 _3) is a thunk

    // => (_1 X2) -> ... -> (X1 X3)
    // ≅ (_1 X2 _3) -> ... -> (X1 X3)
    // ≅ (_1 X2) -> ... -> (X1) -> ... -> (X3)
    // ≅ (_1 X2 _3) ->  ... ->(X1) -> ... -> (X3)
    // ≅ (_1 X2) -> ... -> (X1 _3) -> ... -> (X3)
    // ≅ (_1 X2 _3) -> ... -> (X1 _3) -> ... -> (X3)
    $this->assertEquals($expectedOutput, $step1_1X2(1, 3, 7));
    $this->assertEquals($expectedOutput, $step1_1X2_3(1, 3, 7));
    $this->assertEquals($expectedOutput, $step2_1X2__X1(3, 7));
    $this->assertEquals($expectedOutput, $step2_1X2_3__X1(3, 7));
    $this->assertEquals($expectedOutput, $step2_1X2__X1_3(3, 7));
    $this->assertEquals($expectedOutput, $step2_1X2_3__X1_3(3, 7));

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
    $this->assertInstanceOf(Closure, $step2X1___2X3);
    $this->assertTrue($step2X1___2X3 === $step2X1___2X3());
    $this->assertTrue($step2X1___2X3 === $step2X1___2X3(_()));
    // (X1) -> (_2 X3) is a thunk

    $step1X1_2X3 = $arity3(1, _(), 3);
    $this->assertInstanceOf(Closure, $step1X1_2X3);
    $this->assertTrue($step1X1_2X3 === $step1X1_2X3());
    $this->assertTrue($step1X1_2X3 === $step1X1_2X3(_()));
    // (X1 _2 X3) is a thunk

    // => (X1)-> ... -> (_2 X3) -> ... -> (X2)
    // ≅ (X1 _2 X3) -> ... -> (X2)
    $this->assertEquals($expectedOutput, $step2X1___2X3(2, 7));
    $this->assertEquals($expectedOutput, $step1X1_2X3(2, 7));
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
    $this->assertInstanceOf(Closure, $step1_1X2X3);
    $this->assertTrue($step1_1X2X3 === $step1_1X2X3());
    $this->assertTrue($step1_1X2X3 === $step1_1X2X3(_()));
    // (_1 X2 X3) is a thunk

    // Previously we showed that (_1 X2) === (_1 X2 _3) and showed they were a thunk.
    // Skipping proofs for both of these steps

    $step2_1X2___1X3 = $arity3(_(), 2)->__invoke(_(), 3);
    $this->assertInstanceOf(Closure, $step2_1X2___1X3);
    $this->assertTrue($step2_1X2___1X3 === $step2_1X2___1X3());
    $this->assertTrue($step2_1X2___1X3 === $step2_1X2___1X3(_()));
    // (_1 X2) -> (_1 X3) is a thunk

    $step2_1X2_3___1X3 = $arity3(_(), 2)->__invoke(_(), 3);
    $this->assertInstanceOf(Closure, $step2_1X2_3___1X3);
    $this->assertTrue($step2_1X2_3___1X3 === $step2_1X2_3___1X3());
    $this->assertTrue($step2_1X2_3___1X3 === $step2_1X2_3___1X3(_()));
    // (_1 X2 _3) -> (_1 X3) is a thunk

    // => (_1 X2 X3) -> ... -> (X1)
    // ≅ (_1 X2)-> ... -> (_1 X3) -> ... -> (X1)
    // ≅ (_1 X2 _3) -> ... -> (_1 X3) -> ... ->(X1)
    $this->assertEquals($expectedOutput, $step1_1X2X3(1, 7));
    $this->assertEquals($expectedOutput, $step2_1X2___1X3(1, 7));
    $this->assertEquals($expectedOutput, $step2_1X2_3___1X3(1, 7));
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
    $this->assertInstanceOf(Closure, $step2_1_2X3___1X2);
    $this->assertTrue($step2_1_2X3___1X2 === $step2_1_2X3___1X2());
    $this->assertTrue($step2_1_2X3___1X2 === $step2_1_2X3___1X2(_()));
    // (_1 _2 X3) -> (_1 X2) is a thunk

    // => (_1 _2 X3) -> (_1 X2) -> (X1)
    $this->assertEquals($expectedOutput, $step2_1_2X3___1X2(1, 7));
    unset ($step2_1_2X3___1X2);
    /** ∴ (X1 X2 X3) ≅ (_1 _2 X3) -> (_1 X2) -> (X1) ∎ **/


    // Praise the sun! \o/
    // Let us now go forth and engage in jolly cooperation!
  }


  public function test_getDescriptor()
  {
    $this->assertEquals(\stdClass::class, __PRIVATE__::getDescriptor(new \stdClass()));
    $handle = curl_init('http://www.wikipedia.com/');
    $this->assertEquals('curl', __PRIVATE__::getDescriptor($handle));
    curl_close($handle);
    $other = [
      [], [1], 0, 1, -0.0, +0.0, 1.0, NAN, INF, '', '^_^', null, true, false
    ];
    array_walk($other, function ($value) {
      $this->assertEquals(gettype($value), __PRIVATE__::getDescriptor($value));
    });
  }

  /**
   * @param callable $callable
   * @dataProvider callableProvider
   */
  public function test_assertCallable($callable)
  {
    $this->assertTrue(
      $callable === internal::assertCallable($callable)
      , 'it should return the value if it doesn\'t throw an error'
    );
  }

  /**
   * @param mixed $notCallable
   * @dataProvider  notCallableProvider
   */
  public function test_assertCallable_exception($notCallable)
  {
    $this->expectException(\InvalidArgumentException::class);
    internal::assertCallable($notCallable);
  }

  public function test_assertCollection()
  {
    $collection = \PHPixme\None();
    $this->assertInstanceOf(
      CollectionInterface::class
      , $collection
      , 'serious problem if this is not true. Failing because of None!'
    );
    $this->assertTrue(
      $collection === internal::assertCollection($collection)
      , 'it should return the collection identity if no error is thrown'
    );
  }

  public function test_assertCollection_exception()
  {
    $this->expectException(\UnexpectedValueException::class);
    internal::assertCollection(new \stdClass());
  }

  /**
   * @param int $value
   * @dataProvider assertPositiveOrZeroProvider
   */
  public function test_assertPositiveOrZero($value)
  {
    $this->assertTrue(
      $value === internal::assertPositiveOrZero($value)
      , 'the function, when not throwing an error, returns the identity'
    );
  }

  /**
   * @param $extraDomain
   * @dataProvider assertPositiveOrZeroNotProvider
   */
  public function test_assertPositiveOrZero_exception($extraDomain)
  {
    $this->expectException(\InvalidArgumentException::class);
    internal::assertPositiveOrZero($extraDomain);
  }

  /**
   * @param $arrayLike
   * @dataProvider assertTraversableProvider
   */
  public function test_assertTraversable($arrayLike)
  {
    $this->assertTrue(
      $arrayLike === internal::assertTraversable($arrayLike)
      , 'the function should return the identity when the contract is met.'
    );
  }

  /**
   * @param $notTransversable
   * @dataProvider assertTraversableNotProvider
   */
  public function test_assertTraversable_exception($notTransversable)
  {
    $this->expectException(\InvalidArgumentException::class);
    internal::assertTraversable($notTransversable);
  }


  public function test_copyTransversable()
  {
    $arr = [1, 2, 3];
    $result = internal::copyTransversable($arr);
    $arr[0] = 0;
    $this->assertTrue(is_array($result), 'Should return an array on an array input');
    $this->assertNotEquals($arr, $result, 'If php is working, this should automatically work.');
    $iter = new \ArrayIterator($arr);

    $result = internal::copyTransversable($iter);
    $this->assertInstanceOf(\Iterator::class, $result);
    $this->assertInstanceOf(\ArrayIterator::class, $result);
    $this->assertInstanceOf(get_class($result), $result);
    $this->assertTrue($iter == $result);
    $this->assertFalse($iter === $result);

    $iterAggr = new \ArrayObject($arr);
    $result = internal::copyTransversable($iterAggr);
    $this->assertInstanceOf(\Iterator::class, $result);
    $this->assertInstanceOf(
      get_class($iterAggr->getIterator())
      , $result
      , 'it should not clone iterator aggregates, rather just get an instance of a iterator.'
    );

  }

  public function test_copyTransversable_broken_contract()
  {
    $this->expectException(\InvalidArgumentException::class);
    internal::copyTransversable(null);
  }

  /**
   * @dataProvider internalInstanceProvider
   * @param string $key
   */
  public function test_instanceFunctions($key)
  {
    // This is a canary for something horribly mismatched.
    $closure = internal::$instance[$key];
    $this->assertInstanceOf(Closure, $closure);
    $this->assertInstanceOf(Closure, $closure(), 'the thunk should return a closure');
    // Guarding for false positives of Pipe and Combine, as they are variadic,
    // and will never return their identity, as the final length will always be indeterminate
    // and it will always make a new function each step.
    if (false === array_search($key, static::fnIdentityBlacklist)) {
      $this->assertTrue($key()===$closure, 'Thunk currying should return the value stored in internal');
    }
  }
  const fnIdentityBlacklist = [PHPixme.'\combine', PHPixme.'\pipe'];

  public function callableProvider()
  {
    $object = new TestClass();
    $closure = function ($x) {
      return $x;
    };
    return [
      'static function' => [\PHPixme\I]
      , 'closures' => [$closure]
      , 'static method string' => [TestClass::class . '::testStatic']
      , 'array-class static method' => [[TestClass::class, 'testStatic']]
      , 'array-object method' => [[$object, 'getArgs']]
      , 'array-object static method' => [[$object, 'testStatic']]
      , 'invokable objects' => [new invokable()]
    ];
  }

  public function notCallableProvider()
  {
    $dummy = new TestClass();
    return [
      'object with no invoke' => [$dummy]
      , 'invalid string to static function' => ['']
      , 'invalid string to static method' => ['::']
      , 'integers' => [1]
      , 'floats' => [1.0]
      , 'null' => [null]
      , 'bad arrays' => [[$dummy, '']]
    ];
  }

  public function assertPositiveOrZeroProvider()
  {
    return [
      [1]
      , [0]
      , [100]
    ];
  }

  public function assertPositiveOrZeroNotProvider()
  {
    return [
      [null]
      , [false]
      , ['']
      , [-1]
      , [-100]
      , [new \stdClass()]
      , [1.0]
    ];
  }

  public function assertTraversableProvider()
  {
    return [
      [[]]
      , [[1]]
      , [new \ArrayIterator([])]
      , [new \ArrayIterator([1])]
    ];
  }

  public function assertTraversableNotProvider()
  {
    return [
      [new \stdClass()]
      , [new TestClass()]
      , [1]
      , [null]
      , [1.0]
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
}



