<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Success as testSubject;
use function PHPixme\Success as testNew;
use const PHPixme\Success as testConst;
use PHPixme\Failure as oppositeSubject;

/**
 * Class SuccessTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Success
 */
class SuccessTest extends \PHPUnit_Framework_TestCase
{

  /** @coversNothing */
  public function test_constants()
  {
    self::assertSame(testSubject::class, testConst);
    self::assertTrue(function_exists(testSubject::class));
    self::assertNotEquals(
      getParent(testSubject::class)->getConstant(shortName)
      , testSubject::shortName
      , 'It should define its own'
    );
  }

  /**
   * @coversNothing
   */
  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\RightHandedTrait::class, $traits);
  }

  /** @covers ::__construct */
  public function test_new($value = false)
  {
    $result = new testSubject($value);

    self::assertAttributeSame($value, 'value', $result);
  }

  /** @coversNothing */
  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(1))->__construct(2);
  }

  /** @covers PHPixme\Success */
  public function test_companion($value = false)
  {
    $subject = testNew($value);
    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($value), $subject);
  }

  /** @covers ::of */
  public function test_applicative($value = false)
  {
    $subject = testSubject::of($value);

    self::assertInstanceOf(P\ApplicativeInterface::class, $subject);
    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($value), $subject);
  }

  /**
   * @covers ::isSuccess
   * @covers ::isFailure
   * @covers ::isEmpty
   * @covers ::isLeft
   * @covers ::isRight
   */
  public function test_is_status($value = null)
  {
    $subject = testNew($value);
    self::assertTrue($subject->isSuccess());
    self::assertFalse($subject->isFailure());
    self::assertFalse($subject->isEmpty());
    self::assertFalse($subject->isLeft());
    self::assertTrue($subject->isRight());
  }


  /**
   * @dataProvider getProvider
   * @covers ::get
   */
  public function test_get($value)
  {
    self::assertSame($value, testNew($value)->get());
  }

  /**
   * @dataProvider getProvider
   * @covers ::merge
   */
  public function test_merge($value)
  {
    self::assertSame($value, testNew($value)->merge());
  }

  /** @covers ::getOrElse */
  public function test_getOrElse($value = true, $default = false)
  {
    self::assertSame($value, (testNew($value)->getOrElse($default)));
  }

  /** @covers ::orElse */
  public function test_orElse($value = true)
  {
    $subject = testNew($value);

    self::assertSame($subject, $subject->orElse(doNotRun));
  }

  /** @coversNothing */
  public function test_filter_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);
    $test = function () use ($value, $subject, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return true;
    };

    // Apply and release any captured assertions
    $subject->filter($test)->get();

    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /** @covers ::filter */
  public function test_filter_thrown($value = true)
  {
    $success = testNew($value);
    $thrownValue = new \Exception('test');
    $failureThrown = $success->filter(function () use ($thrownValue) {
      throw $thrownValue;
    });

    self::assertInstanceOf(oppositeSubject::class, $failureThrown);
    self::assertSame($thrownValue, $failureThrown->merge());
  }

  /** @covers ::filter */
  public function test_filter_return($value = true)
  {
    $success = testNew($value);

    self::assertSame($success, $success->filter(bTrue));
    self::assertInstanceOf(oppositeSubject::class, $success->filter(bFalse));
  }

  /** @coversNothing */
  function test_flatMap_callback($value = true)
  {
    $ran = 0;
    $contents = testNew($value);
    $subject = testNew($contents);
    $test = function () use ($subject, $contents, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($contents, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);

      $ran += 1;
      return $v;
    };

    // Apply and release any captured assertions
    $subject->flatMap($test)->get();

    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /** @coversNothing */
  function test_flatMap_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatMap(P\I);
  }

  /** @covers ::flatMap */
  public function test_flatMap_throw()
  {
    $contents = new \Exception('test');
    $results = testNew($contents)->flatMap(P\toss);

    self::assertInstanceOf(oppositeSubject::class, $results);
    self::assertSame($contents, $results->merge());
  }

  /** @covers ::flatMap */
  public function test_flatMap_return()
  {
    $lhsContents = testSubject::ofLeft(new \Exception());
    $rhsContents = testNew(1);

    self::assertSame($rhsContents, testSubject::of($rhsContents)->flatMap(identity));
    self::assertSame($lhsContents, testSubject::of($lhsContents)->flatMap(identity));
  }

  /** @covers ::flatten */
  function test_flatten_return()
  {
    $lhsContents = testSubject::ofLeft(new \Exception());
    $rhsContents = testNew(1);

    self::assertSame($lhsContents, testNew($lhsContents)->flatten());
    self::assertSame($rhsContents, testNew($rhsContents)->flatten());
  }


  /** @coversNothing */
  function test_flatten_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatten();
  }

  /** @covers ::flattenRight */
  function test_flattenRight_return()
  {
    $lhsContents = testSubject::ofLeft(new \Exception());
    $rhsContents = testNew(1);

    self::assertSame($lhsContents, testNew($lhsContents)->flattenRight());
    self::assertSame($rhsContents, testNew($rhsContents)->flattenRight());
  }


  /** @coversNothing */
  function test_flattenRight_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flattenRight();
  }

  /** @covers ::failed */
  function test_failed($value = true)
  {
    self::assertInstanceOf(oppositeSubject::class, testNew($value)->failed());
  }

  /** @coversNothing */
  function test_map_callback($value = true)
  {
    $ran = 0;
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

  /** @covers ::map */
  function test_map_return($value = true)
  {
    $success = testNew($value);

    $result = $success->map(identity);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($success, $result);
    self::assertEquals($success, $result);
  }

  /** @covers ::apply */
  public function test_apply($expected = 50)
  {
    $ran = 0;
    $testFn = function () use (&$ran, $expected) {
      $ran +=1;
      return $expected;
    };
    $subject = testNew($testFn);
    $functor = testNew(null);

    $result = $subject->apply($functor);

    self::assertGreaterThan(0, $ran);
    self::assertEquals($functor->map($testFn), $result);

  }

  /** @coversNothing  */
  public function test_apply_contract()
  {
    $this->expectException(P\exception\InvalidContentException::class);
    testNew(null)->apply(testNew(null));
  }


  /** @covers ::recover */
  public function test_recover($value = true)
  {
    $success = testNew($value);

    self::assertSame($success, $success->recover(doNotRun));
  }

  /** @covers ::recoverWith */
  public function test_recoverWith($value = true)
  {
    $success = testNew($value);

    self::assertSame($success, $success->recoverWith(doNotRun));
  }

  /** @coversNothing */
  public function test_fold_callback($value = true, $startValue = false)
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

  /** @covers ::fold */
  public function test_fold_return($value = 1, $startVal = 2)
  {
    $success = testNew($value);
    $add2 = function ($x, $y) {
      return $x + $y;
    };

    self::assertEquals($add2($startVal, $value), $success->fold($add2, $startVal));
  }

  /** @coversNothing */
  public function test_foldRight_callback($value = true, $startValue = false)
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

  /** @covers ::foldRight */
  public function test_foldRight_return($value = 1, $startVal = 2)
  {
    $success = testNew($value);
    $add2 = function ($x, $y) {
      return $x + $y;
    };

    self::assertEquals($add2($startVal, $value), $success->foldRight($add2, $startVal));
  }

  /** @coversNothing */
  function test_forAll_callback($value = true)
  {
    $ran = 0;
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
    self::assertSame(1, $ran, 'the callback should of ran');;
  }

  /** @covers ::forAll */
  function test_forAll_return($value = 'once')
  {
    $success = testNew($value);

    self::assertTrue($success->forAll(bTrue));
    self::assertFalse($success->forAll(bFalse));
  }

  /** @coversNothing */
  function test_forNone_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->forNone(function () use ($subject, $value, &$ran) {
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

  /** @covers ::forNone */
  function test_forNone_return($value = 'once')
  {
    $success = testNew($value);

    self::assertFalse($success->forNone(bTrue));
    self::assertTrue($success->forNone(bFalse));
  }

  /** @coversNothing */
  function test_forSome_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->forSome(function () use ($subject, $value, &$ran) {
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

  /** @covers ::forSome */
  function test_forSome_return($value = 'once')
  {
    $success = testNew($value);

    self::assertTrue($success->forSome(bTrue));
    self::assertFalse($success->forSome(bFalse));
  }


  /** @covers ::toArray */
  public function test_toArray($value = true)
  {
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  /** @covers ::toUnbiasedDisjunctionInterface */
  public function test_toUnbiasedDisjunctionInterface($value = 1)
  {
    $subject = testNew($value);

    $result = $subject->toUnbiasedDisjunctionInterface();

    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $result);
    self::assertInstanceOf(P\RightHandSideType::class, $result);
    self::assertEquals($subject->isLeft(), $result->isLeft());
    self::assertEquals($subject->isRight(), $result->isRight());
    self::assertSame($value, $result->merge());

  }

  /** @covers ::toMaybe */
  public function test_toMaybe($value = true)
  {
    $result = testNew($value)->toMaybe();
    self::assertInstanceOf(P\Some::class, $result);
    self::assertEquals(new P\Some($value), $result);
  }

  /** @coversNothing */
  function test_find_callback($value = true)
  {
    $ran = 0;
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

  /** @covers ::find */
  function test_find_return($value = 'once')
  {
    $subject = testNew($value);
    $found = $subject->find(bTrue);
    $missing = $subject->find(bFalse);

    self::assertInstanceOf(P\Some::class, $found);
    self::assertSame($value, $found->get());
    self::assertInstanceOf(P\None::class, $missing);
  }

  /** @coversNothing */
  public function test_transform_callback($value = true)
  {
    $ran = 0;
    $subject = testNew($value);
    $test = function () use ($value, $subject, &$ran) {
      self::assertEquals(2, func_num_args());
      list($v, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertSame($subject, $t);

      $ran += 1;
      return $subject;
    };

    // Apply and release any captured assertions
    $subject->transform($test, doNotRun)->get();

    self::assertSame(1, $ran, 'the callback should of ran');
  }

  /** @coversNothing */
  public function test_transform_broken_contract()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(true)->transform(noop, noop);
  }

  /** @covers ::transform */
  public function test_transform_return()
  {
    $subject = testNew(null);
    $success = testNew(null);
    $switchToSuccess = function () use ($success) {
      return $success;
    };
    $failure = oppositeSubject::of(new \Exception('test'));
    $switchToFailure = function () use ($failure) {
      return $failure;
    };

    self::assertSame($success, $subject->transform($switchToSuccess, doNotRun));
    self::assertSame($failure, $subject->transform($switchToFailure, doNotRun));
  }


  /** @covers ::transform */
  public function test_transform_throw($value = true)
  {
    $exception = new \Exception('test');
    $throwException = function () use ($exception) {
      throw $exception;
    };

    $result = testNew($value)->transform($throwException, doNotRun);

    self::assertInstanceOf(oppositeSubject::class, $result);
    self::assertSame($exception, $result->merge());
  }

  /** @coversNothing */
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

  /** @covers ::walk */
  public function test_walk($value = true)
  {
    $subject = testNew($value);
    self::assertSame($subject, $subject->walk(noop));
  }

  /** @covers ::count */
  public function test_count($value = [])
  {
    $success = testNew($value);
    self::assertEquals(1, count($success));
    self::assertEquals(1, $success->count());
  }

  /** @covers ::getIterator */
  public function test_traversable($value = [])
  {
    $ran = 0;
    $success = testNew($value);

    foreach ($success as $k => $v) {
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      $ran += 1;
    }
    self::assertEquals(1, $ran);
  }

  public function getProvider()
  {
    return [
      'null' => [null]
      , 'bool' => [false]
      , 'Object' => [new \stdClass()]
      , 'array' => [[]]
      , 'integer' => [100]
      , 'float' => [100.1]
      , 'string' => ["Hi!"]
      , 'Left hand side' => [oppositeSubject::of(new \Exception('Test Exception'))]
    ];
  }

}
