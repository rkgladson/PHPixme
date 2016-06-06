<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Failure as testSubject;
use function PHPixme\Failure as testNew;
use const PHPixme\Failure as testConst;
use PHPixme\Success as oppositeSubject;

/**
 * Class FailureTest
 * @package tests\PHPixme
 * @coversDefaultClass PHPixme\Failure
 */
class FailureTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
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
  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\Failure::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\LeftHandedTrait::class, $traits);
    self::assertContains(P\NothingCollectionTrait::class, $traits);
  }

  /**
   * @coversNothing
   */
  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(valueE()))->__construct(valueE());
  }

  /**
   * @covers ::__construct
   */
  public function test_new() {
    $value = valueE();

    self::assertAttributeSame($value, 'err', new testSubject($value));
  }

  /**
   * @covers PHPixme\Failure
   */
  public function test_companion()
  {
    $contents = valueE();

    $subject = testNew($contents);

    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($contents), $subject);
  }

  /**
   * @covers ::of
   */
  public function test_applicative()
  {
    $value = valueE();

    $result = testSubject::of($value);

    self::assertInstanceOf(P\ApplicativeInterface::class, $result);
    self::assertEquals(new testSubject($value), $result);
  }

  /**
   * @covers ::merge
   */
  public function test_merge() {
    $value = valueE();

    self::assertSame($value, testNew($value)->merge());
  }

  /**
   * @covers ::isSuccess
   * @covers ::isFailure
   * @covers ::isEmpty
   * @covers ::isLeft
   * @covers ::isRight
   */
  public function test_is_status()
  {
    $subject = testNew(valueE());

    self::assertFalse($subject->isSuccess());
    self::assertTrue($subject->isFailure());
    self::assertTrue($subject->isEmpty());
    self::assertTrue($subject->isLeft());
    self::assertFalse($subject->isRight());
  }

  /**
   * @covers ::get
   */
  public function test_failure_get()
  {
    $exception = valueE();
    $subject = testNew($exception);
    try {
      $subject->get();
    } catch (\Exception $e) {
      self::assertSame($exception, $e);
    }
  }

  /**
   * @covers ::getOrElse
   */
  public function test_getOrElse($default = 10)
  {
    $subject = testNew(valueE());

    self::assertSame($default, $subject->getOrElse($default));
  }


  /**
   * @coversNothing
   */
  public function test_orElse_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(valueE())->orElse(noop);
  }

  /**
   * @covers ::orElse
   */
  public function test_orElse_return()
  {
    $success = new oppositeSubject(null);
    $toSuccess = function () use ($success) {
      return $success;
    };
    $failure = testNew(valueE());
    $keepFailing = function () use ($failure) {
      return $failure;
    };
    $subject = testNew(valueE());

    self::assertSame($success, $subject->orElse($toSuccess));
    self::assertSame($failure, $subject->orElse($keepFailing));
  }

  /**
   * @covers ::orElse
   */
  public function test_orElse_throw()
  {
    $exception = valueE();
    $subject = testNew(valueE());
    $throw = function () use ($exception) {
      throw $exception;
    };

    $results = $subject->orElse($throw);

    self::assertInstanceOf(testSubject::class, $results);
    self::assertSame($exception, $results->merge());
  }

  /**
   * @covers ::filter
   */
  public function test_filter()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->filter(doNotRun));
  }

  /**
   * @covers ::flatMap
   */
  public function test_flatMap()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->flatMap(doNotRun));
  }

  /**
   * @covers ::flatten
   */
  public function test_flatten()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->flatten());
  }

  /**
   * @covers ::flattenRight
   */
  public function test_flattenRight()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->flattenRight());
  }

  /**
   * @covers ::failed
   */
  public function test_failed()
  {
    $value = valueE();
    $failure = testNew($value);

    $result = $failure->failed();

    self::assertInstanceOf(oppositeSubject::class, $result);
    self::assertSame($value, $result->merge());
  }

  /**
   * @covers ::map
   */
  public function test_map()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->map(doNotRun));
  }

  /**
   * @covers ::fold
   */
  public function test_fold($value = true)
  {
    self::assertSame($value, testNew(valueE())->fold(doNotRun, $value));
  }

  /**
   * @covers ::foldRight
   */
  public function test_foldRight($value = true)
  {
    self::assertSame($value, testNew(valueE())->foldRight(doNotRun, $value));
  }

  /**
   * @coversNothing
   */
  public function test_recover_callback()
  {
    $value = valueE();
    $ran = 0;
    $subject = testNew($value);

    $subject->recover(function () use ($value, $subject, &$ran) {
      self::assertEquals(2, func_num_args());
      list($v, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertSame($subject, $t);

      $ran += 1;
      return $value;
    });
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::recover
   */
  public function test_recover_throw()
  {
    $exception = valueE();
    $throwTest = function () use ($exception) {
      throw $exception;
    };
    $subject = testSubject::of(valueE());

    $results = $subject->recover($throwTest);

    $this->assertInstanceOf(testSubject::class, $results);
    $this->assertSame($exception, $results->merge());
  }


  /**
   * @covers ::recover
   */
  public function test_recover_return()
  {
    $recoveryValue = new \stdClass();
    $recoverFn = function () use ($recoveryValue) {
      return $recoveryValue;
    };
    $subject = testNew(valueE());

    $results = $subject->recover($recoverFn);

    self::assertInstanceOf(oppositeSubject::class, $results);
    self::assertSame($recoveryValue, $results->merge());
  }


  /**
   * @coversNothing
   */
  public function test_recoverWith_callback()
  {
    $value = valueE();
    $ran = 0;
    $subject = testNew($value);
    //FIXME: This will eat assertions
    $subject->recover(function () use ($value, $subject, &$ran) {
      self::assertEquals(2, func_num_args());
      list ($v, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertSame($subject, $t);

      $ran += 1;
      return $subject;
    });
    self::assertEquals(1, $ran);
  }

  /**
   * @coversNothing
   */
  public function test_recoverWith_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(valueE())->recoverWith(noop);
  }

  /**
   * @covers ::recoverWith
   */
  public function test_recoverWith_throw()
  {
    $exception = valueE();
    $throw = function () use ($exception) {
      throw $exception;
    };
    $subject = testSubject::of(valueE());

    self::assertEquals(testSubject::of($exception), $subject->recoverWith($throw));
  }

  /**
   * @covers ::recoverWith
   */
  public function test_recoverWith_return()
  {
    $subject = testNew(valueE());
    $success = oppositeSubject::of(null);
    $failure = testSubject::of(valueE());
    $swapWithSuccess = function () use ($success) {
      return $success;
    };
    $swapWithFailure = function () use ($failure) {
      return $failure;
    };

    self::assertSame($success, $subject->recoverWith($swapWithSuccess));
    self::assertSame($failure, $subject->recoverWith($swapWithFailure));
  }


  /**
   * @covers ::toArray
   */
  public function test_toArray()
  {
    $value = valueE();
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  /**
   * @covers ::toUnbiasedDisjunctionInterface
   */
  public function test_toUnbiasedDisjunctionInterface () {
    $value = valueE();
    $subject = testNew($value);

    $result = $subject->toUnbiasedDisjunctionInterface();

    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $result);
    self::assertInstanceOf(P\LeftHandSideType::class, $result);
    self::assertEquals($subject->isLeft(), $result->isLeft());
    self::assertEquals($subject->isRight(), $result->isRight());
    self::assertSame($value, $result->merge());
  }

  /**
   * @covers ::toMaybe
   */
  public function test_toMaybe()
  {
    self::assertInstanceOf(P\None::class, testNew(valueE())->toMaybe());
  }

  /**
   * @covers ::find
   */
  public function test_find()
  {
    self::assertInstanceOf(P\None::class, testNew(valueE())->find(doNotRun));
  }

  /**
   * @covers ::forAll
   * @covers ::forNone
   * @covers ::forSome
   */
  public function test_for_all_none_some()
  {
    $subject = testNew(valueE());

    self::assertTrue($subject->forAll(doNotRun));
    self::assertTrue($subject->forNone(doNotRun));
    self::assertFalse($subject->forSome(doNotRun));
  }

  /**
   * @coversNothing
   */
  public function test_transform_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(valueE())->transform(noop, noop);
  }

  /**
   * @coversNothing
   */
  public function test_transform_callback()
  {
    $value = valueE();
    $ran = 0;
    $subject = testNew($value);
    $test = function () use ($value, $subject, &$ran) {
      self::assertEquals(2, func_num_args());
      list($v, $t) = func_get_args();

      self::assertSame($value, $v);
      self::assertSame($subject, $t);

      $ran += 1;
      // Convert to a success so we can catch assertions
      return new oppositeSubject($subject);
    };

    // Apply and release any captured assertions
    $subject->transform(doNotRun, $test)->get();

    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  /**
   * @covers ::transform
   */
  public function test_transform_return()
  {
    $subject = testNew(valueE());
    $success = oppositeSubject::of(null);
    $failure = testNew(valueE());
    $switchToSuccess = function () use ($success) {
      return $success;
    };
    $switchToFailure = function () use ($failure) {
      return $failure;
    };

    self::assertSame($success, $subject->transform(doNotRun, $switchToSuccess));
    self::assertSame($failure, $subject->transform(doNotRun, $switchToFailure));
  }

  /**
   * @covers ::transform
   */
  public function test_transform_throw()
  {
    $exception = valueE();
    $throw = function () use ($exception) {
      throw $exception;
    };

    $result = testNew(valueE())->transform(doNotRun, $throw);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertSame($exception, $result->merge());
  }


  /**
   * @covers ::walk
   */
  public function test_walk()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->walk(doNotRun));
  }

  /**
   * @covers ::count
   */
  public function test_count()
  {
    $subject = testNew(valueE());
    self::assertEquals(0, count($subject));
    self::assertEquals(0, $subject->count());
  }

  /**
   * @covers ::getIterator
   */
  public function test_transversable()
  {
    $count = 0;
    foreach (testNew(valueE()) as $value) {
      $count += 1;
    }
    self::assertEquals(0, $count);
  }
}

function valueE()
{
  return new \Exception(microtime());
} 