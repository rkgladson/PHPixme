<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 4:03 PM
 */

namespace tests\PHPixme;

use PHPixme as P;


class FailureTest extends \PHPUnit_Framework_TestCase
{
  public function test_Failure_constants()
  {
    $this->assertTrue(
      P\Failure::class === P\Failure
      , 'The constant for the Class and Function should be equal to the Class Path'
    );
    $this->assertTrue(
      function_exists(P\Failure::class)
      , 'The companion function exists for the class.'
    );
  }

  public function test_Failure_companion()
  {
    $this->assertInstanceOf(
      P\Failure::class
      , P\Failure(new \Exception('Test Exception'))
      , 'It should return failure instances'
    );
  }

  public function test_static_creation () {
    $ofMade = P\Failure::of(new \Exception());
    $this->assertInstanceOf(P\ApplicativeInterface::class, $ofMade);
    $this->assertInstanceOf(P\Failure::class, $ofMade);
  }

  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\Failure::class));
    $this->assertTrue(
      false !== array_search(P\ClosedTrait::class, $traits)
      , 'should be closed'
    );
  }

  public function test_patience(){
    $this->expectException(P\exception\MutationException::class);
    $exception = new \Exception();
    (new P\Failure($exception))->__construct($exception);
  }

  public function test_is_status()
  {
    $failure = P\Failure(new \Exception('test'));
    $this->assertTrue(
      $failure->isFailure()
      , 'Failure->isFailure should be true'
    );
    $this->assertFalse(
      $failure->isSuccess()
      , 'Failure->isSuccess should be false'
    );
    $this->assertTrue(
      $failure->isEmpty()
      , 'Failure->isEmpty should be true'
    );
  }

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage Test Message
   */
  public function test_failure_get()
  {
    P\Failure(new \Exception('Test Message'))->get();
  }

  public function test_getOrElse($default = 10)
  {
    $this->assertTrue(
      $default === (P\Failure(new \Exception('Test'))->getOrElse($default))
      , 'Failure->getOrElse should return the default value'
    );
  }


  public function test_orElse_scenario_substitute_success($value = '$yay')
  {
    $failure = P\Failure(new \Exception('Test'));
    $default = P\Success($value);
    $getDefault = function () use ($default) {
      return $default;
    };
    $this->assertTrue(
      $default === ($failure->orElse($getDefault))
      , 'Failure->orElse should select the default, even if it returns a Success'
    );
  }

  public function test_orElse_scenario_substitute_failure()
  {
    $default = P\Failure(new \Exception('Test2'));
    $failure = P\Failure(new \Exception('Test1'));
    $getDefault = function () use ($default) {
      return $default;
    };
    $this->assertTrue(
      $default === ($failure->orElse($getDefault))
      , 'Failure->orElse should be the default, even if it returns a Failure'
    );
  }

  public function test_orElse_scenario_recover_thrown_exception()
  {
    $exception = new \Exception('test');
    $initialFailure = P\Failure(new \Exception('^_^'));
    $thrownFailure = $initialFailure->orElse(function () use ($exception) {
      throw $exception;
    });
    $this->assertInstanceOf(
      P\Failure
      , $thrownFailure
      , 'Failure->orElse should return an error on thrown'
    );
    $this->assertTrue(
      $initialFailure !== $thrownFailure
      , 'Failure->orElse on thrown should not be an identity'
    );
    $this->assertTrue(
      $thrownFailure->failed()->get() === $exception
      , 'Failure->orElse returned failure should contain the Exception thrown'
    );
  }

  /**
   * Assure the contract of Failure->orElse is maintained
   * @expectedException \UnexpectedValueException
   */
  public function test_orElse_contract_broken()
  {
    P\Failure(new \Exception('test'))->orElse(function () {
    });
  }

  public function test_filter()
  {
    $failure = P\Failure(new \Exception('test'));
    $this->assertTrue(
      $failure === ($failure->filter(function () {
        return true;
      }))
      , 'Failure->filter is an identity'
    );
  }

  public function test_flatMap()
  {
    $failure = P\Failure(new \Exception('test'));
    $this->assertTrue(
      $failure === ($failure->flatMap(function () {
        return P\Success(true);
      }))
      , 'Failure->flatMap is an identity'
    );
  }

  public function test_flatten()
  {
    $failure = P\Failure(new \Exception('test'));
    $this->assertTrue(
      $failure === ($failure->flatten())
      , 'Failure->flatten is an identity'
    );
  }

  public function test_failed()
  {
    $origErr = new \Exception('test');
    $failure = P\Failure($origErr);
    $success = $failure->failed();
    $this->assertInstanceOf(
      P\Success
      , $success
      , 'Failure->failed should result in a Success'
    );
    $this->assertTrue(
      $origErr === ($success->get())
      , 'Failure->failed resultant Success  should contain the original error'
    );
  }

  public function test_map()
  {
    $failure = P\Failure(new \Exception());
    $this->assertTrue(
      $failure === ($failure->map(function () {
        return 1;
      }))
      , 'Failure->map is an identity'
    );
  }

  public function test_fold($value = true)
  {
    $this->assertEquals(
      $value
      , P\Failure(new \Exception('test'))
      ->fold(
        function () {
          throw new \Exception('Should never run.');
        }
        , $value
      )
    );
  }
  public function test_foldRight($value = true)
  {
    $this->assertEquals(
      $value
      , P\Failure(new \Exception('test'))
      ->foldRight(
        function () {
          throw new \Exception('Should never run.');
        }
        , $value
      )
    );
  }

  public function test_recover_callback()
  {
    $exc = new \Exception('test');
    $failure = P\Failure($exc);
    $failure->recover(function () use ($exc, $failure) {
      $this->assertTrue(
        2 === func_num_args()
        , 'Failure->recover callback should be passed two arguments'
      );
      $this->assertTrue(
        $exc === func_get_arg(0)
        , 'Failure->recover callback $value be its contents'
      );
      $this->assertTrue(
        $failure === func_get_arg(1)
        , 'Failure->recover callback $container should be itself'
      );
      return $exc;
    });
  }

  /**
   * @depends test_failed
   */
  public function test_recover_success()
  {
    $failure = P\Failure(new \Exception('test'));

    $results = $failure->recover(function () {
      return true;
    });
    $this->assertInstanceOf(
      P\Success
      , $results
      , 'Failure->recover callback who returns should be a success'
    );
    $this->assertTrue(
      $results->get()
      , 'Failure->recover results should contain the value that was returned by the callback'
    );
  }

  /**
   * @depends test_failed
   */
  public function test_recover_failure()
  {
    $failure = P\Failure(new \Exception('^_^'));
    $excTest = new \Exception('Test');
    $throwTest = function () use ($excTest) {
      throw $excTest;
    };
    $results = $failure->recover($throwTest);
    $this->assertInstanceOf(
      P\Failure
      , $results
      , 'Failure->recover should result in a failure if the callback throws'
    );
    $this->assertTrue(
      $excTest === ($results->failed()->get())
      , 'Failure->recover returned failure should contain the exception thrown'
    );
  }


  public function test_recoverWith_contract()
  {

    $exc = new \Exception('test');
    $failure = P\Failure($exc);
    $failure->recover(function () use ($exc, $failure) {
      $this->assertTrue(
        2 === func_num_args()
        , 'Failure->recoverWith callback should be passed two arguments'
      );
      $this->assertTrue(
        $exc === func_get_arg(0)
        , 'Failure->recoverWith callback $value be its contents'
      );
      $this->assertTrue(
        $failure === func_get_arg(1)
        , 'Failure->recoverWith callback $container should be itself'
      );
      return $failure;
    });
  }

  /**
   * Ensure the contract is maintained that if the type is broken, it throws an exception
   * @expectedException \UnexpectedValueException
   */
  public function test_recoverWith_contract_broken()
  {
    P\Failure(new \Exception('test'))
      ->recoverWith(function () {
        return '^_^';
      });
  }

  public function test_recoverWith_success($value = true)
  {
    $success = P\Success($value);
    $determination = function () use ($success) {
      return $success;
    };
    $results = P\Failure(new \Exception('Test'))->recoverWith($determination);
    $this->assertTrue(
      $results === $success
      , 'Failure->recoverWith should be able to recoverWith a Success value'
    );
  }

  public function test_recoverWith_failure()
  {
    $failure = new \Exception('^_^');
    $failRecover = function () use ($failure) {
      throw $failure;
    };

    $results = P\Failure(new \Exception('Test'))->recoverWith($failRecover);
    $this->assertInstanceOf(
      P\Failure
      , $results
      , 'On a thrown value, the resultant will always return a failure type'
    );
    $this->assertTrue(
      $results->failed()->get() === $failure
      , 'Failure->recoverWith should pass the excpetion thrown within it to a Failure'
    );
  }


  public function test_toArray()
  {
    $err = new \Exception('test');
    $result = P\Failure($err)->toArray();
    $this->assertTrue(
      is_array($result)
      , 'Failure->toArray should result in array'
    );
    $this->assertTrue(
      $err === $result[P\Failure::shortName]
      , 'Failure->toArray should return contain "failure"=>Exception'
    );
    $this->assertNotTrue(
      array_key_exists(P\Success::shortName, $result)
      , 'Failure->toArray should not contain a "success" key'
    );
  }

  public function test_toMaybe()
  {
    $this->assertInstanceOf(
      P\None
      , P\Failure(new \Exception('test'))->toMaybe()
      , 'Failure->toMaybe should result in None'
    );
  }

  public function test_find()
  {
    $this->assertInstanceOf(
      P\None::class
      , P\Failure(new \Exception('test'))->find(function () {
      throw new \Exception('should never run');
    })
      , 'Failure->find should return the instance of None'
    );
  }

  public function test_for_all_none_some()
  {
    $doNotRun = function () {
      throw new \Exception('should never run');
    };
    $failure = P\Failure(new \Exception('test'));
    $this->assertTrue(
      $failure->forAll($doNotRun)
      , '->forAll should return true on empty'
    );
    $this->assertTrue(
      $failure->forNone($doNotRun)
      , '->forNone should return true on empty'
    );
    $this->assertFalse(
      $failure->forSome($doNotRun)
      , '->forSome should return false on empty'
    );
  }

  public function test_transform_callback($value = 'test')
  {
    $exc = new \Exception($value);
    $fail = P\Failure($exc);
    P\Failure(new \Exception($value))->transform(function () {
      throw new \Exception('This should not be run!');
    }, function () use ($exc, $fail) {
      $this->assertTrue(
        2 === func_num_args()
        , 'Failure->transform  callback receive two arguments'
      );
      $this->assertTrue(
        $exc === func_get_arg(0)
        , 'Failure->transform callback $value should be its contents'
      );
      $this->assertTrue(
        $fail === func_get_arg(1)
        , 'Failure->transform callback $container should be itself'
      );
      return $fail;
    });
  }

  /**
   * @param string $value
   */
  public function test_transform_scenario_to_success($value = 'test')
  {
    $fail = P\Failure(new \Exception($value));
    $this->assertTrue(
      $value === ($fail->transform(
        function () {
        },
        function (\Exception $value) {
          return P\Success($value->getMessage());
        }
      )->get())
      , 'Failure->transform through the failure callback can become a Success'
    );
  }

  public function test_transform_scenario_to_failure()
  {
    $fail = P\Failure(new \Exception('Test'));
    $secondFailure = P\Failure(new \Exception('Test'));
    $this->assertTrue(
      $secondFailure === ($fail->transform(
        function () {
        },
        function () use ($secondFailure) {
          return $secondFailure;
        }
      ))
      , 'Failure->transform through the failure callback can remain a Failure'
    );
  }

  /**
   * @expectedException \UnexpectedValueException
   */
  public function test_transform_contract_broken()
  {
    $bad = function () {
    };
    P\Failure(new \Exception())->transform($bad, $bad);
  }

  public function test_walk()
  {
    $notRun = function () {
      throw new \Exception('Failure->walk callback should not be run!');
    };
    P\Failure(new \Exception())->walk($notRun);
  }

  public function test_count()
  {
    $failure = P\Failure(new \Exception('test'));
    $this->assertInstanceOf(
      \Countable::class
      , $failure
    );
    $this->assertTrue(
      0 === count($failure)
      , 'count(Failure) should return 0'
    );
    $this->assertTrue(
      0 === $failure->count()
      , 'failure->count() should return 0'
    );
  }

  public function test_transversable() {
    $failure = P\Failure(new \Exception('test'));
    $this->assertInstanceOf(
      \IteratorAggregate::class
      , $failure
      , 'It should implement a Iterator Aggregate'
    );
    $count = 0;
    foreach($failure as $value){
      $count +=1;
    }
    $this->assertEquals(0, $count);
  }
}
