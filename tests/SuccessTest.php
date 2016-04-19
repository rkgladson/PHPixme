<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 3:22 PM
 */
namespace tests\PHPixme;

use PHPixme as P;

class SuccessTest extends \PHPUnit_Framework_TestCase
{

  public function test_Success_constants()
  {
    $this->assertTrue(
      P\Success::class === P\Success
      , 'The constant for the Class and Function should be equal to the Class Path'
    );
    $this->assertTrue(
      function_exists(P\Success)
      , 'The companion function exists for the class.'
    );
  }

  public function test_Success_companion()
  {
    $this->assertStringEndsWith(
      '\Success'
      , P\Success
      , 'Ensure the constant ends with the function/class name'
    );
    $this->assertInstanceOf(
      P\Success,
      P\Success(false),
      'the companion function should return an instance of its class'
    );
  }

  public function test_is_status()
  {

    $success = P\Success(true);
    $this->assertTrue(
      $success->isSuccess()
      , 'it should be a success'
    );
    $this->assertFalse(
      $success->isFailure()
      , 'it should not be a failure'
    );
  }


  public function getProvider()
  {
    return [
      [null, 'should be able to store then retrieve null']
      , [new \stdClass(), 'should be able to store then retrieve another built in class']
      , [[], 'should be able to store then retrieve arrays']
      , [100, 'should be able to store then retrieve numbers']
      , ["Hi!", 'should be able to store then retrieve strings']
      , [P\Failure(new \Exception('Test Exception')), 'Should be able to contain and retrieve failures']
    ];
  }

  /**
   * @dataProvider getProvider
   */
  public function test_get($value, $message)
  {
    $this->assertTrue(
      $value === (P\Success($value)->get())
      , $message
    );
  }

  public function test_getOrElse($value = true, $default = false)
  {
    $this->assertTrue(
      $value === (P\Success($value)->getOrElse($default))
      , 'Success->getOrElse should be an its contents'
    );
  }

  public function test_orElse($value = true)
  {
    $instance = P\Success($value);
    $this->assertTrue(
      $instance === ($instance->orElse(function () use ($value) {
        return P\Success($value);
      }))
      , 'Success->orElse should be an identity'
    );
  }


  public function test_filter_callback($value = true)
  {
    $success = P\Success($value);
    $success->filter(function () use ($value, $success) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Success->filter callback should receive 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'Success->filter callback $value should be its contents'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'Success->filter callback $key should be defined'
      );
      $this->assertTrue(
        $success === func_get_arg(2)
        , 'Success->filter callback $container should be itself'
      );
      return true;
    });
  }

  public function test_filter($value = true)
  {
    $success = P\Success($value);
    $this->assertTrue(
      $success === ($success->filter(function () {
        return true;
      }))
      , 'Success->filter When a receiving a true should be an identity'
    );

    $false = function () {
      return false;
    };
    $this->assertInstanceOf(
      P\Failure
      , $success->filter($false)
      , 'Success->filter When receiving a false should become a Failure'
    );

    $thrownValue = new \Exception('test');
    $failureThrown = $success->filter(function () use ($thrownValue) {
      throw $thrownValue;
    });
    $this->assertInstanceOf(
      P\Failure
      , $failureThrown
      , 'Succes->filter when having an error thrown should return type failure'
    );
    $this->assertTrue(
      $thrownValue === $failureThrown->failed()->get()
      , 'Success->filter should return the failure containing the exception'
    );

  }


  function test_flatMap_callback($value = true)
  {
    $child = P\Success($value);
    $success = P\Success($child);
    $success->flatMap(function () use ($success, $child) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Success->flatMap should pass three arguments to the callback'
      );
      $this->assertTrue(
        $child === func_get_arg(0)
        , 'Success->flatMap callback $value should be its contents'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'Success->flatMap callback $key should be defined'
      );
      $this->assertTrue(
        $success === func_get_arg(2)
        , 'Success->flatMap $container should be itself'
      );
      return true;
    });
  }


  /**
   * Ensure that flatMap throws an exception if the callback does not honor it's callback
   * @expectedException \UnexpectedValueException
   */
  function test_flatMap_contract_broken()
  {
    P\Success(true)->flatMap(function () {
    });
  }

  function test_flatMap_scenario_contains_success($value = true)
  {
    $child = P\Success($value);
    $success = P\Success($child);
    $flatten = function ($value) {
      return $value;
    };
    $this->assertTrue(
      $child === ($success->flatMap($flatten))
      , 'Success->flatMap should return its contained Success'
    );
  }

  function test_flatMap_scenario_contains_failure()
  {
    $flatten = function ($value) {
      return $value;
    };
    $this->assertInstanceOf(
      P\Failure
      , P\Success(P\Failure(new \Exception()))->flatMap($flatten)
      , 'Success->flatMap shouldn\'t care if the contents returned is a Failure'
    );
  }


  function test_flatten_scenario_success($value = true)
  {
    $child = P\Success($value);
    $parent = P\Success($child);
    $this->assertTrue(
      $child === ($parent->flatten())
      , 'Success->flatten should return its contained Success'
    );

  }

  public function test_flatten_scenario_contains_failure()
  {
    $child = P\Failure(new \Exception());
    $parent = P\Success($child);
    $this->assertTrue(
      $child === ($parent->flatten())
      , 'Success->flatten should return its contained Failure'
    );
  }

  /**
   * Ensure flatten calls an exception if the object violate's it own contract
   * @expectedException \UnexpectedValueException
   */
  function test_flatten_contract_broken($value = true)
  {
    P\Success($value)->flatten();
  }

  function test_failed($value = true)
  {
    $this->assertInstanceOf(
      P\Failure
      , P\Success($value)->failed()
      , 'Success->failed produces a Failure'
    );
  }


  function test_map_callback($value = true)
  {
    $success = P\Success($value);
    $success->map(function () use ($value, $success) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Success->map callback should receive 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'Success->map callback $value should be equal to what is contained'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'Success->map callback $key should be defined, even if its not so useful'
      );
      $this->assertTrue(
        $success === func_get_arg(2)
        , 'Success->map callback $container should be itself'
      );
      return true;
    });
  }

  /**
   * @depends test_get
   */
  function test_map($value = true)
  {
    $success = P\Success($value);
    $one = function () {
      return 'one';
    };
    $result = $success->map($one);
    $this->assertInstanceOf(
      P\Success
      , $result
      , 'Success->map should stay a Success'
    );
    $this->assertFalse(
      $success === $result
      , 'Success->map should not return Success of the same instance'
    );
    $this->assertTrue(
      'one' === ($result->get())
      , 'Success->map should have the correct results'
    );
  }

  public function test_recover($value = true)
  {
    $success = P\Success($value);
    $this->assertTrue(
      $success === ($success->recover(function () {
      }))
      , 'Success->Recover is an identity'
    );
  }

  public function test_recoverWith($value = true)
  {
    $success = P\Success($value);
    $this->assertTrue(
      $success === ($success->recoverWith(function () {
      }))
      , 'Success->RecoverWith is an identity'
    );
  }

  public function test_toArray($value = true)
  {

    $result = P\Success($value)->toArray();
    $this->assertTrue(
      $result['success']
      , 'Success->toArray method should return an array ["success" => contents]'
    );
    $this->assertNotTrue(
      isset($result['failure'])
      , 'Success->toArray results should not contain a failure key'
    );
  }

  public function test_toMaybe($value = true)
  {
    $result = P\Success($value)->toMaybe();
    $this->assertInstanceOf(
      P\Some
      , $result
      , 'Success->toMaybe should result in Some'
    );
    $this->assertTrue(
      $value === ($result->get())
      , 'Success->toMaybe resultant Some should contain the same value'
    );
  }


  public function test_transform_callback($value = true)
  {
    $success = P\Success($value);
    $success->transform(function () use ($value, $success) {
      $this->assertTrue(
        2 === func_num_args()
        , 'Success->transform signature contains two arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'Success->transform callback $value its contents'
      );
      $this->assertTrue(
        $success === func_get_arg(1)
        , 'Success->transform $container should be itself'
      );
      return $success;
    }
      , function () {
        throw new \Exception('Success->transform should never run the failure path!');
      }
    );
  }

  public function test_transform_scenario_success_to_success($value = true)
  {
    $thing1 = P\Success($value);
    $thing2 = P\Success($value);
    $noop = function () {
    };
    $switchToThing2 = function () use ($thing2) {
      return $thing2;
    };
    $this->assertTrue(
      $thing2 === $thing1->transform($switchToThing2, $noop)
      , 'Success->transform should return the results of its success path callback'
    );


  }

  public function test_transform_scenario_success_to_failure($value = true)
  {
    $failure = P\Failure(new \Exception('test'));
    $makeFailure = function () use ($failure) {
      return $failure;
    };
    $noop = function () {
    };
    $this->assertTrue(
      $failure === P\Success($value)->transform($makeFailure, $noop)
      , 'Success->transform Success callback type should be able to be a Failure'
    );
  }

  public function test_transform_scenario_thrown_to_failure($value = true)
  {
    $exception = new \Exception('test');
    $noop = function () {
    };
    $result = P\Success($value)->transform(function () use ($exception) {
      throw $exception;
    }, $noop);
    $this->assertInstanceOf(
      P\Failure
      , $result
      , 'Success->transform should return Failure on thrown'
    );
    $this->assertTrue(
      $result->failed()->get() === $exception
      , 'Succes->transformation should return the Exception within the failure of what was thrown.'
    );
  }


  /**
   * Ensure that Transform throws an exception if the callbacks violate the contract
   * @expectedException \UnexpectedValueException
   */
  public function test_transform_broken_contract()
  {
    P\Success(true)->transform(function () {
    }, function () {
    });
  }


  public function test_walk_callback($value = true)
  {
    $success = P\Success($value);
    $success->walk(function () use ($value, $success) {
      $this->assertTrue(
        3 === func_num_args()
        , 'Success->walk should pass three arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'Success->walk callback $value should be its contents'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'Success->walk callback $key should be defined'
      );
      $this->assertTrue(
        $success === func_get_arg(2)
        , 'Success->walk should pass container as itself'
      );
    });
  }

  public function test_walk($value = true)
  {
    $success = P\Success($value);
    $ran = 0;
    $success->walk(function () use (&$ran) {
      $ran += 1;
    });
    $this->assertTrue(
      1 === $ran
      , 'Success->walk should of ran only one time'
    );
  }


}
