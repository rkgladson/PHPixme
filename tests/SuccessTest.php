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
            'the companion function should return an instance of it\'s class'
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


}
