<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/20/2016
 * Time: 12:53 PM
 */

namespace tests\PHPixme;

use PHPixme\__PRIVATE__ as internal;
use PHPixme\CollectionInterface;
use function PHPixme\_ as _;
const closure = \Closure::class;
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
      closure
      , $arity1
      , 'It should wrap the function in a closure'
    );
    $thunk = $arity1();
    $this->assertInstanceOf(
      closure
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
      closure
      , $arity2
      , 'it should wrap into a closure'
    );
    // Testing thunks
    $this->assertInstanceOf(
      closure
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
    $arity3 = internal::curryExactly3(function (...$args) {
      return $args;
    });
    $expectedOutput = [1,2,3];
    $this->assertInstanceOf(
      closure
      , $arity3
      , 'it should wrap into a closure'
    );
    // Testing thunks
    $this->assertInstanceOf(
      closure
      , $arity3()
      , 'thunks should return a closure'
    );
    $this->assertTrue(
      $arity3 === $arity3()
      , 'at zero arguments, that closure should be itself'
    );
    $this->assertTrue(
      $arity3 === $arity3(_())
      , 'thunks should have no effect'
    );
    $this->assertTrue(
      $arity3() === $arity3(_(), _())
      , 'thunks should have no effect'
    );
    $this->assertTrue(
      $arity3() === $arity3(_(), _(), _())
      , 'thunks should have no effect'
    );
    // (X1 X2 X3) Group
    $this->assertEquals(
      $expectedOutput
      , $arity3(1,2,3,4,5)
    );
    // (X1)->(X2)->(X3) Sequence
    $step1 = $arity3(1);
    // Prove that step 1 is a thunk
    $this->assertInstanceOf(closure, $step1);
    $this->assertTrue($step1 === $step1());
    $this->assertTrue($step1 === $step1(_()));
    $this->assertTrue($step1 === $step1(_(), _()));
    $step2 = $step1(2);
    // Prove that step2 is a thunk
    $this->assertInstanceOf(closure, $step2);
    $this->assertTrue($step2 === $step2());
    $this->assertTrue($step2 === $step2(_()));
    $this->assertTrue($step2 === $step2(_(), _()));
    // Show that 3rd does an application
    $this->assertEquals($expectedOutput, $step2(3,4,5));
    unset($step1, $step2);
    // (X1)->(X2 X3) Sequence
    // (X1 X2) -> (X3) Sequence

    // (X1 X2 _3) Group
    $intermediate = $arity3(1,2);
    $pIntermediate = $arity3(1,2,_());
    $this->assertInstanceOf(closure, $intermediate);
    $this->assertInstanceOf(closure, $pIntermediate);
    $this->assertEquals($expectedOutput, $intermediate(3,4,5));
    $this->assertEquals($intermediate(3,7), $pIntermediate(3,2));
    $this->assertTrue($intermediate === $intermediate());
    $this->assertTrue($intermediate === $intermediate(_()));
    $this->assertTrue($pIntermediate === $pIntermediate());
    $this->assertTrue($pIntermediate === $pIntermediate(_()));
    unset($pIntermediate, $intermediate);

    // (_1 X2 X3) Group
    $intermediate = $arity3(_(),2,3);
    $this->assertInstanceOf(closure, $intermediate);
    $this->assertTrue($intermediate === $intermediate());
    $this->assertTrue($intermediate === $intermediate(_()));
    $this->assertEquals($expectedOutput, $intermediate(1,7,9,4,5,6));
    unset($intermediate);

    // (X1 _2 X3) group
    $intermediate = $arity3(1,_(),3);
    $this->assertInstanceOf(closure, $intermediate);
    $this->assertTrue($intermediate === $intermediate());
    $this->assertTrue($intermediate === $intermediate(_()));
    $this->assertEquals($expectedOutput, $intermediate(2,7,9,5,6));
    unset($intermediate);

    // (X1 _2 _3) Group
    $intermediate = $arity3(1);
    $p1Intermediate = $arity3(1, _());
    $p2Intermediate = $arity3(1,_(),_());
    $this->assertInstanceOf(closure, $intermediate);
    $this->assertInstanceOf(closure, $p1Intermediate);
    $this->assertInstanceOf(closure, $p2Intermediate);
    $this->assertTrue($intermediate === $intermediate());
    $this->assertTrue($intermediate === $intermediate(_()));
    $this->assertTrue($p1Intermediate === $p1Intermediate());
    $this->assertTrue($p1Intermediate === $p1Intermediate(_()));
    $this->assertTrue($p2Intermediate === $p2Intermediate());
    $this->assertTrue($p2Intermediate === $p2Intermediate(_()));
    $this->assertEquals($expectedOutput, $intermediate(2,3,7));
    $this->assertEquals($expectedOutput, $p1Intermediate(2,3,7));
    $this->assertEquals($expectedOutput, $p2Intermediate(2,3,7));

    unset($intermediate, $p1Intermediate, $p2Intermediate, $temp);
    // (_1 X2 _3)
    // (_1 _2 X3)


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

  }

  /**
   * @param $notTransversable
   * @dataProvider assertTraversableNotProvider
   */
  public function test_assertTraversable_exception($notTransversable)
  {

  }

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
}



