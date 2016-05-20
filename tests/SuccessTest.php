<?php
namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\Success as testSubject;
use function PHPixme\Success as testNew;
use const PHPixme\Success as testConst;
use PHPixme\Failure as oppositeSubject;

class SuccessTest extends \PHPUnit_Framework_TestCase
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

  public function test_companion($value = false)
  {
    $subject = testNew($value);
    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($value), $subject);
  }

  public function test_applicative($value = false)
  {
    $subject = testSubject::of($value);
    self::assertInstanceOf(P\ApplicativeInterface::class, $subject);
    self::assertInstanceOf(testSubject::class, $subject);
    self::assertEquals(new testSubject($value), $subject);
  }

  public function test_traits()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
    self::assertContains(P\RightHandedTrait::class, $traits);
  }

  public function test_patience()
  {
    $this->expectException(P\exception\MutationException::class);
    (new testSubject(1))->__construct(2);
  }

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
   */
  public function test_get($value)
  {
    self::assertSame($value, (testNew($value)->get()));
  }

  public function test_getOrElse($value = true, $default = false)
  {
    self::assertSame($value, (testNew($value)->getOrElse($default)));
  }

  public function test_orElse($value = true)
  {
    $subject = testNew($value);
    $result = ($subject->orElse(function () use ($value) {
      P\toss($value);
    }));
    self::assertSame($subject, $result);
  }

  public function test_filter_callback($value = true)
  {
    $ran = 0;
    $success = testNew($value);

    $success->filter(function () use ($value, $success, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();
      self::assertSame($value, $v);
      self::assertEquals(0, $k);
      self::assertSame($success, $t);
      $ran += 1;
      return true;
    });
    self::assertSame(1, $ran, 'the callback should of ran');
  }


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

  public function test_filter_return($value = true)
  {
    $success = testNew($value);
    $trueResult = $success->filter(function () {
      return true;
    });
    $falseResult = $success->filter(function () {
      return false;
    });

    self::assertSame($success, $trueResult);
    self::assertInstanceOf(oppositeSubject::class, $falseResult);
  }

  function test_flatMap_callback($value = true)
  {
    $ran = 0;
    $contents = testNew($value);
    $subject = testNew($contents);
    $results = $subject->flatMap(function () use ($subject, $contents, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $t) = func_get_args();

      self::assertSame($contents, $v);
      self::assertEquals(0, $k);
      self::assertSame($subject, $t);
      $ran += 1;
      return $v;
    });

    self::assertSame($contents, $results, 'it should of not ate a assertion');
    self::assertSame(1, $ran, 'the callback should of ran');
  }

  function test_flatMap_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatMap(P\I);
  }

  public function test_flatMap_throw()
  {
    $contents = new \Exception('test');
    $results = testNew($contents)->flatMap(P\toss);

    self::assertInstanceOf(oppositeSubject::class, $results);
    self::assertSame($contents, $results->merge());
  }

  public function test_flatMap_return()
  {
    $lhsContents = testSubject::ofLeft(new \Exception());
    $rhsContents = testNew(1);
    $flatten = function ($value) {
      return $value;
    };

    self::assertSame($rhsContents, (testSubject::of($rhsContents)->flatMap($flatten)));
    self::assertSame($lhsContents, (testSubject::of($lhsContents)->flatMap($flatten)));
  }

  function test_flatten_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flatten();
  }


  function test_flatten_return()
  {
    $lhsContents = testSubject::ofLeft(new \Exception());
    $rhsContents = testNew(1);

    self::assertSame($lhsContents, testNew($lhsContents)->flatten());
    self::assertSame($rhsContents, testNew($rhsContents)->flatten());
  }

  function test_flattenRight_contract_broken()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(null)->flattenRight();
  }


  function test_flattenRight_return()
  {
    $lhsContents = testSubject::ofLeft(new \Exception());
    $rhsContents = testNew(1);

    self::assertSame($lhsContents, testNew($lhsContents)->flattenRight());
    self::assertSame($rhsContents, testNew($rhsContents)->flattenRight());
  }

  function test_failed($value = true)
  {
    $result = testNew($value)->failed();

    self::assertInstanceOf(oppositeSubject::class, $result);
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


  function test_map_return($value = true)
  {
    $success = testNew($value);
    $one = function () {
      return 'one';
    };
    $result = $success->map($one);

    self::assertInstanceOf(testSubject::class, $result);
    self::assertNotSame($success, $result);
    self::assertSame($one($value), ($result->get()));
  }

  public function test_recover($value = true)
  {
    $success = testNew($value);
    self::assertSame($success, $success->recover(doNotRun));
  }

  public function test_recoverWith($value = true)
  {
    $success = testNew($value);
    self::assertSame($success, $success->recoverWith(doNotRun));
  }

  public function test_fold_callback($value = true, $startValue = false)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->fold(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertTrue($startValue === $s);
      self::assertTrue($value === $v);
      self::assertEquals(0, $k);
      self::assertTrue($subject === $t);
      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  public function test_fold_return($value = 1, $startVal = 2)
  {
    $success = testNew($value);
    $add2 = function ($x, $y) {
      return $x + $y;
    };

    self::assertEquals($add2($startVal, $value), $success->fold($add2, $startVal));
  }

  public function test_foldRight_callback($value = true, $startValue = false)
  {
    $ran = 0;
    $subject = testNew($value);

    $subject->foldRight(function () use ($subject, $value, $startValue, &$ran) {
      self::assertEquals(4, func_num_args());
      list($s, $v, $k, $t) = func_get_args();

      self::assertTrue($startValue === $s);
      self::assertTrue($value === $v);
      self::assertEquals(0, $k);
      self::assertTrue($subject === $t);
      $ran += 1;
      return $s;
    }, $startValue);
    self::assertEquals(1, $ran, 'the callback should of ran');
  }

  public function test_foldRight_return($value = 1, $startVal = 2)
  {
    $success = testNew($value);
    $add2 = function ($x, $y) {
      return $x + $y;
    };

    self::assertEquals($add2($startVal, $value), $success->foldRight($add2, $startVal));
  }


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


  function test_forAll_return($value = 'once', $notValue = 'and for ALL!')
  {
    $success = testNew($value);
    self::assertTrue($success->forAll(function ($x) use ($value) {
      return $x === $value;
    }));
    self::assertFalse($success->forAll(function ($x) use ($notValue) {
      return $x === $notValue;
    }));
  }

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


  function test_forNone_return($value = 'once', $notValue = 'and for ALL!')
  {
    $success = testNew($value);
    self::assertFalse($success->forNone(function ($x) use ($value) {
      return $x === $value;
    }));
    self::assertTrue($success->forNone(function ($x) use ($notValue) {
      return $x === $notValue;
    }));
  }

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


  function test_forSome_return($value = 'once', $notValue = 'and for ALL!')
  {
    $success = testNew($value);
    self::assertTrue($success->forSome(function ($x) use ($value) {
      return $x === $value;
    }));
    self::assertFalse($success->forSome(function ($x) use ($notValue) {
      return $x === $notValue;
    }));
  }

  public function test_toArray($value = true)
  {
    self::assertEquals([testSubject::shortName => $value], testNew($value)->toArray());
  }

  public function test_toUnbiasedDisjunctionInterface ($value = 1) {
    $subject = testNew($value);
    
    $result = $subject->toUnbiasedDisjunctionInterface();
    
    self::assertInstanceOf(P\UnbiasedDisjunctionInterface::class, $result);
    self::assertInstanceOf(P\RightHandSideType::class, $result);
    self::assertEquals($subject->isLeft(), $result->isLeft());
    self::assertEquals($subject->isRight(), $result->isRight());
    self::assertSame($value, $result->merge());
    
  }

  public function test_toMaybe($value = true)
  {
    $result = testNew($value)->toMaybe();
    self::assertInstanceOf(P\Some::class, $result);
    self::assertEquals(new P\Some($value), $result);
  }


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


  function test_find_return($value = 'once', $notValue = 'and for ALL!')
  {
    $subject = testNew($value);
    $found = $subject->find(function ($v) use ($value) {
      return $value === $v;
    });
    $missing = $subject->find(function ($v) use ($notValue) {
      return $notValue === $v;
    });

    self::assertInstanceOf(P\Some::class, $found);
    self::assertTrue($value === $found->get());
    self::assertInstanceOf(P\None::class, $missing);
  }

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

    $subject->transform($test, doNotRun);

    self::assertSame(1, $ran, 'the callback should of ran');
  }

  public function test_transform_broken_contract()
  {
    $this->expectException(\UnexpectedValueException::class);
    testNew(true)->transform(noop, noop);
  }


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

  public function test_walk($value = true)
  {
    $subject = testNew($value);
    self::assertTrue($subject === $subject->walk(noop));
  }

  public function test_count($value = [])
  {
    $success = testNew($value);
    self::assertEquals(1, count($success));
    self::assertEquals(1, $success->count());
  }

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

}
