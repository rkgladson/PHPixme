<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 4:25 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Pot as testSubject;
use const PHPixme\Pot as testConst;
use function PHPixme\Pot as testNew;
use PHPixme\exception\InvalidContentException as invalidContent;
use PHPixme\exception\InvalidReturnException as invalidReturn;

/**
 * Class PotTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Pot
 */
class PotTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_existential_companion()
  {
    self::assertEquals(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
  }

  /**
   * @covers       PHPixme\Pot
   * @dataProvider valueProvider
   */
  public function test_companion_function($value = true)
  {
    $result = testNew($value);
    $this->assertInstanceOf(testSubject::class, $result);
    $this->assertEquals(new testSubject($value), $result);
  }

  /**
   * @coversNothing
   */
  public function test_aspects()
  {
    $subject = new \ReflectionClass(testSubject::class);

    self::assertTrue(is_a(testSubject::class, \Exception::class, true));
    foreach ([
               P\CollectionInterface::class
               , P\UnaryApplicativeInterface::class
               , \Countable::class
             ] as $interface) {
      self::assertTrue($subject->implementsInterface($interface));
    }
  }

  /**
   * @coversNothing
   */
  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertNotContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);

  }

  /**
   * @coversNothing
   */
  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(0))->__construct(1);
  }

  /**
   * @covers ::__construct
   * @dataProvider valueProvider
   */
  public function test_constructor($value)
  {
    $message = 'happy birthday';
    $code = 8675309;
    $previous = new \Exception();

    $result = new P\Pot($value, $message, $code, $previous);

    self::assertAttributeSame($value, 'contents', $result);
    self::assertEquals($message, $result->getMessage());
    self::assertEquals($code, $result->getCode());
    self::assertSame($previous, $result->getPrevious());
  }


  /**
   * @covers ::of
   * @dataProvider valueProvider
   */
  public function test_applicative($value = true)
  {
    $result = testSubject::of($value);

    $this->assertInstanceOf(testSubject::class, $result);
    $this->assertEquals(new testSubject($value), $result);
  }

  /**
   * @covers ::fromThrowable
   */
  public function test_fromThrowable($value = true, $message = 'Hoi!', $code = 404)
  {
    $exception = new \Exception($message, $code);
    $pottedException = testSubject::fromThrowable($exception, $value);
    self::assertInstanceOf(testSubject::class, $pottedException);
    self::assertEquals($value, $pottedException->get());
    self::assertEquals($message, $pottedException->getMessage());
    self::assertEquals($code, $pottedException->getCode());
    self::assertSame($exception, $pottedException->getPrevious());
  }


  /**
   * @covers ::__invoke
   * @dataProvider valueProvider
   */
  public function test_invocation($value = true)
  {
    $subject = new testSubject($value);

    self::assertSame($value, $subject());
  }

  /**
   * @covers ::get
   * @dataProvider valueProvider
   */
  public function test_get($value = true)
  {
    $subject = new testSubject($value);

    self::assertSame($value, $subject->get());
  }

  /**
   * @coversNothing
   * @dataProvider valueProvider
   */
  public function test_map_callback($value)
  {
    $subject = testNew($value);

    $subject->map(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::map
   * @dataProvider valueProvider
   */
  public function test_map($value)
  {
    $previous = new \Exception();
    $subject = new testSubject($value, 'howdy', -1, $previous);

    $result = $subject->map(identity);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($subject, $result);
    self::assertEquals($subject, $result);
    self::assertSame($value, $result());
  }

  /**
   * @coversNothing
   */
  public function test_apply_contract() {
    $this->expectException(invalidContent::class);
    testNew(null)->apply(testNew(null));
  }

  /**
   * @covers ::apply
   */
  public function test_apply() {
    $ran = 0;
    $expect = 1;
    $testFn = function () use (&$ran, $expect) {
      $ran +=1;
      return $expect;
    };
    $functor = testNew(null);

    $result = testNew($testFn)->apply($functor);

    self::assertGreaterThan(0, $ran);
    self::assertEquals($functor->map($testFn), $result);
  }

  /**
   * @coversNothing
   * @dataProvider foldCallbackProvider
   */
  public function test_fold_callback($value, $startValue)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->fold(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertSame($startValue, $s);
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::fold
   * @dataProvider foldScenarioProvider
   */
  public function test_fold($fn, $value, $startVal, $expectation)
  {
    $subject = testNew($value);

    self::assertEquals($expectation, $subject->fold($fn, $startVal));
  }

  /**
   * @coversNothing
   * @dataProvider foldCallbackProvider
   */
  public function test_foldRight_callback($value, $startValue)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->foldRight(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertSame($startValue, $s);
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::foldRight
   * @dataProvider foldScenarioProvider
   */
  public function test_foldRight(callable $fn, $value, $startVal, $expectation)
  {
    $subject = testNew($value);

    self::assertEquals($expectation, $subject->foldRight($fn, $startVal));
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_callback()
  {
    $ran = 0;
    $value = testNew(null);
    $subject = testNew($value);

    $subject->flatMap(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @coversNothing
   */
  public function test_flatMap_contract_broken()
  {
    $this->expectException(invalidReturn::class);
    testNew(null)->flatMap(noop);
  }

  /**
   * @covers ::flatMap
   */
  public function test_flatMap()
  {
    $value = testNew(null);
    $subject = testNew($value);

    self::assertSame($value, $subject->flatMap(identity));
  }

  /**
   * @covers ::flatten
   */
  public function test_flatten()
  {
    $value = testNew(null);
    $subject = testNew($value);

    $result = $subject->flatten();

    self::assertInstanceOf(testSubject::class, $result);
    self::assertSame($value, $result);
  }

  /**
   * @coversNothing
   */
  public function test_flatten_contract_broken($value = true)
  {
    $this->expectException(invalidContent::class);
    testNew($value)->flatten();
  }

  /**
   * @coversNothing
   */
  public function test_forAll_callback($value = true)
  {
    $subject = testNew($value);

    $subject->forAll(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::forAll
   */
  public function test_forAll($value = true, $notValue = null)
  {
    $pot = testNew($value);
    self::assertTrue(
      $pot->forAll(function ($v) use ($value) {
        return $value === $v;
      })
      , 'A true resultant for when the function returns true'
    );
    self::assertFalse(
      $pot->forAll(function ($v) use ($notValue) {
        return $notValue === $v;
      })
      , 'a false resultant when the function returns false'
    );
  }

  /**
   * @coversNothing
   */
  public function test_forNone_callback($value = true)
  {
    $run = 0;
    $pot = testNew($value);
    $pot->forNone(function () use ($value, $pot, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run += 1;
      return true;
    });
    self::assertEquals(1, $run, 'the callback should of ran once');
  }

  /**
   * @covers ::forNone
   */
  public function test_forNone($value = true, $notValue = null)
  {
    $pot = testNew($value);
    self::assertFalse(
      $pot->forNone(function ($v) use ($value) {
        return $value === $v;
      })
      , 'A false resultant for when the function returns true'
    );
    self::assertTrue(
      $pot->forNone(function ($v) use ($notValue) {
        return $notValue === $v;
      })
      , 'a True resultant when the function returns false'
    );
  }

  /**
   * @coversNothing
   */
  public function test_forSome_callback($value = true)
  {
    $run = 0;
    $subject = testNew($value);
    $subject->forSome(function () use ($value, $subject, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $subject === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run += 1;
      return true;
    });
    self::assertEquals(1, $run, 'the callback should of ran once');
  }

  /**
   * @covers ::forSome
   */
  public function test_forSome()
  {
    $subject = testNew(null);

    self::assertTrue($subject->forSome(bTrue));
    self::assertFalse($subject->forSome(bFalse));
  }

  /**
   * @coversNothing
   */
  public function test_walk_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->walk(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::walk
   */
  public function test_walk()
  {
    $subject = testNew(null);

    self::assertSame($subject, $subject->walk(noop));
  }

  /**
   * @covers ::toArray
   */
  public function test_toArray($value = true)
  {
    self::assertEquals([$value], testNew($value)->toArray());
  }

  /**
   * @coversNothing
   */
  public function test_find_callback($value = true)
  {
    $subject = testNew($value);

    $subject->find(function () use ($subject, $value, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::find
   */
  public function test_find()
  {
    $value = new \stdClass();
    $subject = testNew($value);

    $Some = $subject->find(bTrue);
    $None = $subject->find(bFalse);

    self::assertInstanceOf(P\Some::class, $Some);
    self::assertSame($value, $subject->get());
    self::assertInstanceOf(P\None::class, $None);
  }

  /**
   * @covers ::isEmpty
   */
  public function test_isEmpty()
  {
    self::assertFalse(testNew(null)->isEmpty());
  }


  /**
   * @covers ::getIterator
   */
  public function test_traversable($value = true)
  {
    $iterations = 0;
    $k_ = null;
    $v_ = null;
    foreach (P\Pot($value) as $k => $v) {
      $k_ = $k;
      $v_ = $v;
      $iterations += 1;
    }
    self::assertEquals(
      1
      , $iterations
      , 'It should terminate after one traversal.'
    );
    self::assertEquals(
      $value
      , $v_
      , 'the last stored value should be equal to the value of the container'
    );
    self::assertEquals(
      0
      , $k_
      , 'the last stored key should be the \'index\' of the container'
    );
  }

  /**
   * @covers ::count
   */
  public function test_count($value = true)
  {
    self::assertTrue(
      1 === testNew($value)->count()
      , 'Count should be the constant 1'
    );
  }

  public function valueProvider()
  {
    return [
      [[]]
      , [true]
      , [false]
      , [null]
      , [1]
      , ['^_^']
      , ['']
      , [P\Some(1)]
      , [new \ArrayIterator([1])]
    ];
  }

  public function filledCollectionProvider()
  {
    return [
      [[1]]
      , [[1, 2, 3]]
      , [P\Some(1)]
      , [new \ArrayIterator([1])]
    ];
  }

  public function emptyCollectionProvider()
  {
    return [
      [[]]
      , [P\None()]
      , [P\Seq::from([])]
      , [new \ArrayIterator([])]
    ];
  }

  public function foldCallbackProvider()
  {
    return [
      [1, 0]
      , ['9', '']
    ];
  }

  public function foldScenarioProvider()
  {
    $add = function ($x, $y) {
      return $x + $y;
    };
    $multiply = function ($x, $y) {
      return $x * $y;
    };
    $concat = function ($x, $y) {
      return $x . $y;
    };
    return [
      [$add, 2, 2, 4]
      , [$multiply, 4, 2, 8]
      , [$concat, 'bar', 'foo', 'foobar']
    ];
  }

}