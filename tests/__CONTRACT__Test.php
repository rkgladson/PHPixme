<?php

namespace tests\PHPixme;

use PHPixme\__CONTRACT__ as contract;

use PHPixme\exception\InvalidArgumentException as invalidArgument;
use PHPixme\exception\InvalidContentException as invalidContent;
use PHPixme\exception\InvalidCompositionException as invalidComposition;
use PHPixme\exception\InvalidReturnException as invalidReturn;

/**
 * Class __CONTRACT__Test
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\__CONTRACT__
 */
class __CONTRACT__Test extends \PHPUnit_Framework_TestCase
{

  /**
   * @covers ::argIsAPositiveOrZeroInt
   * @dataProvider argIsAPositiveOrZeroIntProvider
   */
  public function test_argIsAPositiveOrZeroInt($value)
  {
    self::assertEquals($value, contract::argIsAPositiveOrZeroInt($value));
  }

  /**
   * * @covers ::argIsAPositiveOrZeroInt
   * @dataProvider argIsAPositiveOrZeroIntViolatedProvider
   */
  public function test_argIsAPositiveOrZeroInt_exception($extraDomain)
  {
    $this->expectException(invalidArgument::class);
    contract::argIsAPositiveOrZeroInt($extraDomain);
  }

  /**
   * @covers ::argIsACallable
   * @dataProvider callableProvider
   */
  public function test_argIsACallable($callable)
  {
    self::assertSame($callable, contract::argIsACallable($callable));
  }

  /**
   * @covers ::argIsACallable
   * @dataProvider  notCallableProvider
   */
  public function test_argIsACallable_exception($notCallable)
  {
    $this->expectException(invalidArgument::class);
    contract::argIsACallable($notCallable);
  }


  /**
   * @covers ::argIsATraversable
   * @dataProvider traversableProvider
   */
  public function test_argIsATraversable($arrayLike)
  {
    self::assertSame($arrayLike, contract::argIsATraversable($arrayLike));
  }

  /**
   * @covers ::argIsATraversable
   * @dataProvider notTraversableProvider
   */
  public function test_argIsATraversable_exception($notTransversable)
  {
    $this->expectException(\InvalidArgumentException::class);
    contract::argIsATraversable($notTransversable);
  }

  /**
   * @covers ::composedIsACallable
   * @dataProvider callableProvider
   */
  public function test_composedIsACallable($callable)
  {
    self::assertSame($callable, contract::composedIsACallable($callable));
  }

  /**
   * @covers ::composedIsACallable
   * @dataProvider notCallableProvider
   */
  public function test_composedIsACallable_exception($notCallable)
  {
    $this->expectException(invalidComposition::class);
    contract::composedIsACallable($notCallable);
  }

  /**
 * @covers ::contentIsA
 */
  public function test_contentIsA()
  {
    $subject = new \stdClass();

    self::assertSame($subject, contract::contentIsA(\stdClass::class, $subject));
  }

  /**
   * @covers ::contentIsA
   */
  public function test_contentIsA_exception()
  {
    $this->expectException(invalidContent::class);
    contract::contentIsA(\stdClass::class, function () {
    });

  }

  /**
   * @covers ::returnIsA
   */
  public function test_returnIsA()
  {
    $subject = new \stdClass();

    self::assertSame($subject, contract::returnIsA(\stdClass::class, $subject));
  }

  /**
   * @covers ::returnIsA
   */
  public function test_returnIsA_exception()
  {
    $this->expectException(invalidReturn::class);
    contract::returnIsA(\stdClass::class, function () {
    });

  }

  public function argIsAPositiveOrZeroIntProvider()
  {
    return [
      [1]
      , [0]
      , [100]
    ];
  }

  public function argIsAPositiveOrZeroIntViolatedProvider()
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

  public function traversableProvider()
  {
    return [
      [[]]
      , [[1]]
      , [new \ArrayIterator([])]
      , [new \ArrayIterator([1])]
    ];
  }

  public function notTraversableProvider()
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
