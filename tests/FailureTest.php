<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 4:03 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Failure as testSubject;
use function PHPixme\Failure as testNew;
use const PHPixme\Failure as testConst;
use PHPixme\Success as oppositeSubject;

class FailureTest extends \PHPUnit_Framework_TestCase
{
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

  public function test_companion()
  {
    $contents = valueE();

    $subject = testNew($contents);

    self::assertInstanceOf(P\Failure::class, $subject);
    self::assertEquals(new testSubject($contents), $subject);
  }

  public function test_applicative()
  {
    $ofMade = P\Failure::of(new \Exception());
    self::assertInstanceOf(P\ApplicativeInterface::class, $ofMade);
    self::assertInstanceOf(P\Failure::class, $ofMade);
  }

  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\Failure::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\LeftHandedTrait::class, $traits);
    self::assertContains(P\NothingCollectionTrait::class, $traits);
  }

  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(valueE()))->__construct(valueE());
  }

  public function test_is_status()
  {
    $subject = testNew(valueE());
    self::assertFalse($subject->isSuccess());
    self::assertTrue($subject->isFailure());
    self::assertTrue($subject->isEmpty());
    self::assertTrue($subject->isLeft());
    self::assertFalse($subject->isRight());
  }

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

  public function test_getOrElse($default = 10)
  {
    $subject = testNew(valueE());

    self::assertSame($default, $subject->getOrElse($default));
  }


  public function test_orElse_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(valueE())->orElse(noop);
  }

  public function test_orElse_return()
  {
    $subject = testNew(valueE());
    $success = new oppositeSubject(null);
    $failure = testNew(valueE());
    $toSuccess = function () use ($success) {
      return $success;
    };
    $keepFailing = function () use ($failure) {
      return $failure;
    };

    self::assertSame($success, $subject->orElse($toSuccess));
    self::assertSame($failure, $subject->orElse($keepFailing));
  }

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

  public function test_filter()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->filter(doNotRun));
  }

  public function test_flatMap()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->flatMap(doNotRun));
  }

  public function test_flatten()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->flatten());
  }

  public function test_failed()
  {
    $value = valueE();
    $failure = testNew($value);

    $result = $failure->failed();

    self::assertInstanceOf(oppositeSubject::class, $result);
    self::assertSame($value, $result->merge());
  }

  public function test_map()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->map(doNotRun));
  }

  public function test_fold($value = true)
  {
    self::assertSame($value, testNew(valueE())->fold(doNotRun, $value));
  }

  public function test_foldRight($value = true)
  {
    self::assertSame($value, testNew(valueE())->foldRight(doNotRun, $value));
  }

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


  public function test_recoverWith_callback()
  {
    $value = valueE();
    $ran = 0;
    $subject = testNew($value);
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

  public function test_recoverWith_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(valueE())->recoverWith(noop);
  }

  public function test_recoverWith_throw()
  {
    $exception = valueE();
    $throw = function () use ($exception) {
      throw $exception;
    };
    $subject = testSubject::of(valueE());

    self::assertEquals(testSubject::of($exception), $subject->recoverWith($throw));
  }

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


  public function test_toArray()
  {
    $value = valueE();
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  public function test_toMaybe()
  {
    self::assertInstanceOf(P\None::class, testNew(valueE())->toMaybe());
  }

  public function test_find()
  {
    self::assertInstanceOf(P\None::class, testNew(valueE())->find(doNotRun));
  }

  public function test_for_all_none_some()
  {
    $failure = testNew(valueE());
    self::assertTrue($failure->forAll(doNotRun));
    self::assertTrue($failure->forNone(doNotRun));
    self::assertFalse($failure->forSome(doNotRun));
  }

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
      return $subject;
    };

    $subject->transform(doNotRun, $test);

    self::assertSame(1, $ran, 'the callback should of ran');
  }

  public function test_transform_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(valueE())->transform(noop, noop);
  }

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


  public function test_walk()
  {
    $subject = testNew(valueE());
    self::assertSame($subject, $subject->walk(doNotRun));
  }

  public function test_count()
  {
    $subject = testNew(valueE());
    self::assertEquals(0, count($subject));
    self::assertEquals(0, $subject->count());
  }

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