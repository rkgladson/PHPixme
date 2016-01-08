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


    public function test_get()
    {
        $this->assertEquals(
            null
            , P\Success(null)->get()
            , 'should be able to store then retrieve null'
        );
        $this->assertInstanceOf(
            '\stdClass'
            , P\Success(new \stdClass())->get()
            , 'should be able to store then retrieve another built in class'
        );
        $this->assertEquals(
            []
            , P\Success([])->get()
            , 'should be able to store then retrieve arrays'
        );
        $this->assertEquals(
            100
            , P\Success(100)->get()
            , 'should be able to store then retrieve numbers'
        );
        $this->assertEquals(
            "Hi!"
            , P\Success("Hi!")->get()
            , 'should be able to store then retrieve strings'
        );
        $this->assertInstanceOf(
            P\Success
            , P\Success(P\Success("Hi!"))->get()
            , 'should be able to store then retrieve other successes'
        );
        $this->assertInstanceOf(
            P\Failure
            , P\Success(P\Failure(new \Exception('Test Exception')))->get()
            , 'Should be able to contain and retrieve failures'
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
        $this->assertEquals(
            $instance
            , $instance->orElse(function () {
            return P\Success(false);
        })
            , 'Success should return itself rather than it\'s default'
        );
    }

    public function test_filter()
    {
        $success = P\Success(true);
        $testHoF = function () use ($success) {
            $this->assertEquals(
                3
                , func_num_args()
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
            $this->assertEquals(
                func_get_arg(2)
                , $success
                , 'The container for filter should be passed to its hof'
            );
            return true;
        };
        $true = function () {
            return true;
        };
        $false = function () {
            return false;
        };
        $success->filter($testHoF);
        $this->assertEquals($success
            , $success->filter($true)
            , 'When a filter returns true, it should return its own instance'
        );

        $this->assertInstanceOf(
            P\Failure
            , $success->filter($false)
            , 'It should become a failure when a filter fails its test'
        );
    }

    function test_flatMap()
    {
        $child = P\Success(true);
        $success = P\Success($child);
        $testHof = function () use ($success, $child) {
            $this->assertEquals(3, func_num_args());
            $this->assertEquals(
                func_get_arg(0)
                , $child
                , 'The value passed should be what is contained'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Key should be defined, even if its not that useful'
            );
            $this->assertEquals(
                func_get_arg(2)
                , $success
                , 'The container passed should be the object being worked on'
            );
            return true;
        };
        $success->flatMap($testHof);
        $flatten = function ($value) {
            return $value;
        };

        $this->assertEquals(
            $child
            , $success->flatMap($flatten)
            , 'The function should be able to return it\'s nested value'
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
        $this->assertEquals(
            $child
            , $parent->flatten()
            , 'It should flatten a single layer of nested successes'
        );

        $child = P\Failure(new \Exception());
        $parent = P\Success($child);
        $this->assertEquals(
            $child
            , $parent->flatten()
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

    /**
     * @depends test_get
     */
    function test_map()
    {
        $success = P\Success(true);
        $testHof = function () use ($success) {
            $this->assertEquals(
                3
                , func_num_args()
                , 'the callback for map should receive 3 arguments'
            );
            $this->assertTrue(
                func_get_arg(0)
                , 'the value should be equal to what is contained'
            );
            $this->assertNotNull(
                func_get_arg(1)
                , 'the key should be defined, even if its not so useful'
            );
            $this->assertEquals(
                func_get_arg(2)
                , $success
                , 'The container being operated on should be passed'
            );
            return true;
        };
        $success->map($testHof);
        $one = function () {
            return 'one';
        };
        $result = $success->map($one);
        $this->assertInstanceOf(
            P\Success
            , $result
            , 'Map should maintain its container type'
        );
        $this->assertNotEquals(
            $success
            , $result
            , 'Map is a transformation, so it should not be the same instance'
        );
        $this->assertEquals(
            'one'
            , $result->get()
            , 'It should have the correct results from map contained inside'
        );
    }

    public function test_recover()
    {
        $success = P\Success(true);
        $this->assertEquals(
            $success
            , $success->recover(function () {
        })
            , 'Recover is an identity for Success'
        );
    }

    public function test_recoverWith()
    {
        $success = P\Success(true);
        $this->assertEquals(
            $success
            , $success->recoverWith(function () {
        })
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

    public function test_transform()
    {
        $thing1 = P\Success(true);
        $thing2 = P\Success(false);
        $noRun = function () {
            throw new \Exception('Should never run!');
        };
        $switchToThing2 = function () use ($thing2) {
            return $thing2;
        };
        $thing1->transform(
            function () use ($thing1) {
                $this->assertEquals(
                    2
                    , func_num_args()
                    , 'The callback should receive one argument'
                );
                $this->assertTrue(
                    func_get_arg(0)
                    , 'The value should be what Success contained'
                );
                $this->assertEquals(
                    $thing1
                    , func_get_arg(1)
                    , 'The container should be passed'
                );
                return $thing1;
            }
            , $noRun
        );
        $this->assertEquals(
            $thing2
            , $thing1->transform($switchToThing2, $noRun)
            , 'The transformation should be able to produce it\s Attempt return value'
        );
        $this->assertInstanceOf(
            P\Failure
            , $thing2->transform(
            function () {
                return P\Failure(new \Exception('test'));
            }
            , $noRun
        )
            , 'The transformation of success should be able to be converted to annother type'
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

    public function test_walk()
    {
        $value = true;
        $success = P\Success($value);
        $ran = 0;
        $success->walk(function () use ($value, $success, &$ran) {
            $this->assertEquals(
                3
                , func_num_args()
                , 'Walk should pass three arguments'
            );
            $this->assertEquals(
                func_get_arg(0)
                , $value
                , 'Value should be what was contained by Success'
            );
            $this->assertNotFalse(
                func_num_args(1)
                , 'Walk should pass a key value, even if it isn\'t useful on Success'
            );
            $this->assertEquals(
                func_get_arg(2)
                , $success
            );

            $ran +=1;
        });
        $this->assertEquals(
            1
            , $ran
            , 'Walk should of ran one time'
        );
    }


}
