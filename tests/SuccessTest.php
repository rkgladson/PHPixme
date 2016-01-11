<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 3:22 PM
 */
namespace tests\PHPixme;
require_once "tests/PHPixme_TestCase.php";
use PHPixme as P;

class SuccessTest extends PHPixme_TestCase
{

    public function test_success_companion()
    {
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

    public function test_getOrElse()
    {
        $this->assertTrue(
            P\Success(true)->getOrElse(false)
            , 'Success should ignore the default behavior and get its own value'
        );
    }

    public function test_orElse()
    {
        $instance = P\Success(true);
        $this->assertTrue(
            $instance === ($instance->orElse(function () {
                return P\Success(false);
            }))
            , 'Success should return itself rather than its default'
        );
    }


    public function test_filter_callback()
    {
        $success = P\Success(true);
        $success->filter(function () use ($success) {
            $this->assertTrue(
                3 === func_num_args()
                , 'The callback should receive 3 arguments'
            );
            $this->assertTrue(
                func_get_arg(0)
                , 'The value should of been passed with what was contained by Success'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'There should be a key, even though it is meaningless in this context'
            );
            $this->assertTrue(
                $success === func_get_arg(2)
                , 'The container for filter should be passed to its hof'
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
            , 'When a filter returns true, it should return its own instance'
        );

        $false = function () {
            return false;
        };
        $this->assertInstanceOf(
            P\Failure
            , $success->filter($false)
            , 'It should become a failure when a filter fails its test'
        );
    }


    function test_flatMap_callback($value = true)
    {
        $child = P\Success($value);
        $success = P\Success($child);
        $success->flatMap(function () use ($success, $child) {
            $this->assertTrue(
                3 === func_num_args()
                , 'The flatMap should pass three arguments to the callback'
            );
            $this->assertTrue(
                $child === func_get_arg(0)
                , 'The value passed should be what is contained by Success'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Key should be defined, even if its not that useful'
            );
            $this->assertTrue(
                $success === func_get_arg(2)
                , 'The container passed should be the Success being worked on'
            );
            return true;
        });
    }

    function test_flatMap($value = true)
    {
        $child = P\Success($value);
        $success = P\Success($child);


        $flatten = function ($value) {
            return $value;
        };

        $this->assertTrue(
            $child === ($success->flatMap($flatten))
            , 'The function should be able to return is nested value'
        );
        $this->assertInstanceOf(
            P\Failure
            , P\Success(P\Failure(new \Exception()))->flatMap($flatten)
            , 'The function shouldn\'t care if the child is a Failure'
        );
    }

    /**
     * Ensure that flatMap throws an exception if the callback does not honor it's callback
     * @expectedException \Exception
     */
    function test_flatMap_contract_broken()
    {
        P\Success(true)->flatMap(function () {
        });
    }

    function test_flatten()
    {
        $child = P\Success(true);
        $parent = P\Success($child);
        $this->assertTrue(
            $child === $parent->flatten()
            , 'It should flatten a single layer of nested successes'
        );

        $child = P\Failure(new \Exception());
        $parent = P\Success($child);
        $this->assertTrue(
            $child === $parent->flatten()
            , 'It should flatten a single layer with a nested failure'
        );
    }

    /**
     * Ensure flatten calls an exception if the object violate's it own contract
     * @expectedException \Exception
     */
    function test_flatten_contract_broken()
    {
        P\Success(true)->flatten();
    }

    function test_failed()
    {
        $this->assertInstanceOf(
            P\Failure
            , P\Success(true)->failed()
            , 'Calling failed on Success produces a failure'
        );
    }


    function test_map_callback($value = true)
    {
        $success = P\Success($value);
        $success->map(function () use ($value, $success) {
            $this->assertTrue(
                3 === func_num_args()
                , 'the callback for map should receive 3 arguments'
            );
            $this->assertTrue(
                $value === func_get_arg(0)
                , 'the value should be equal to what is contained'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'the key should be defined, even if its not so useful'
            );
            $this->assertTrue(
                $success === func_get_arg(2)
                , 'The container being operated on should be passed'
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
            , 'Map should maintain its container type'
        );
        $this->assertFalse(
            $success === $result
            , 'Map is a transformation, so it should not be the same instance'
        );
        $this->assertTrue(
            'one' === ($result->get())
            , 'It should have the correct results from map contained inside'
        );
    }

    public function test_recover()
    {
        $success = P\Success(true);
        $this->assertTrue(
            $success === ($success->recover(function () {
            }))
            , 'Recover is an identity for Success'
        );
    }

    public function test_recoverWith()
    {
        $success = P\Success(true);
        $this->assertTrue(
            $success === ($success->recoverWith(function () {
            }))
            , 'RecoverWith is an identity for Success'
        );
    }

    public function test_toArray()
    {

        $result = P\Success(true)->toArray();
        $this->assertTrue(
            $result['success']
            , 'Success\'s toArray method should return an array with the exception in it at key "success"'
        );
        $this->assertNotTrue(
            isset($result['failure'])
            , 'It should not contain a failure key'
        );
    }

    public function test_toMaybe()
    {
        $result = P\Success(true)->toMaybe();
        $this->assertInstanceOf(
            P\Some
            , $result
            , 'Success should transform to Some'
        );
        $this->assertTrue(
            $result->get()
            , 'The some should contain the value'
        );
    }


    public function test_transform_callback($value = true)
    {
        $success = P\Success($value);
        $success->transform(function () use ($value, $success) {
            $this->assertTrue(
                2 === func_num_args()
                , 'The callback of Success->transform should receive one argument'
            );
            $this->assertTrue(
                $value === func_get_arg(0)
                , 'The value should be what Success contained'
            );
            $this->assertTrue(
                $success === func_get_arg(1)
                , 'The container should be the Success being opperated on'
            );
            return $success;
        }
            , function () {
                throw new \Exception('Should never run!');
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
            , 'The transformation should be able to produce its attempt return value'
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
            , 'The transformation of success should be able to be converted to another type'
        );
    }

    /**
     * Ensure that Transform throws an exception if the callbacks violate the contract
     * @expectedException \Exception
     */
    public function test_transform_broken_contract()
    {
        P\Success(true)->transform(function () {
        }, function () {
        });
    }


    public function test_walk_callback ($value = true) {
        $success = P\Success($value);
        $success->walk(function () use ($value, $success) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Walk should pass three arguments'
            );
            $this->assertTrue(
                $value === func_get_arg(0)
                , 'Value should be what was contained by Success'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Walk should pass a key value, even if it is not useful on Success'
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
            , 'Walk should of ran one time'
        );
    }


}
