<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 4:03 PM
 */

namespace tests\PHPixme;
require_once "tests/PHPixme_TestCase.php";
use PHPixme as P;


class FailureTest extends PHPixme_TestCase
{
    public function test_failure_companion()
    {
        $this->assertInstanceOf(
            P\Failure
            , P\Failure(new \Exception('Test Exception'))
            , 'It should return failure instances'
        );
    }

    public function test_is_status() {
        $failure = P\Failure(new \Exception('test'));
        $this->assertTrue(
            $failure->isFailure()
            , 'it should be a failure'
        );
        $this->assertFalse(
            $failure->isSuccess()
            , 'it should not be a success'
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

    public function test_getOrElse()
    {
        $this->assertEquals(
            10
            , P\Failure(new \Exception('Test'))->getOrElse(10)
            , 'getOrElse on failure should always return the default value'
        );
    }

    public function test_orElse_complaint()
    {
        $failure = P\Failure(new \Exception('Test'));
        $result = $failure->orElse(function () {
            return P\Success('yay!');
        });
        $this->assertInstanceOf(
            P\Success
            , $result
            , 'it should of subsisted with success'
        );
        $this->assertEquals(
            'yay!'
            , $result->get()
            , 'it should contain the data we defined'
        );
        $result = $failure->orElse(function () {
            return P\Failure(new \Exception('yay!'));
        });
        $this->assertInstanceOf(
            P\Failure
            , $result
            , 'it should of contained the failure we substituted'
        );
    }

    /**
     * Assure the contract is maintained
     * @expectedException \Exception
     */
    public function test_orElse_contract_broken()
    {
        P\Failure(new \Exception('test'))->orElse(function () {});
    }

    public function test_filter() {
        $failure = P\Failure(new \Exception('test'));
        $this->assertEquals(
            $failure
            , $failure->filter(function () {return true;})
            , 'filter for failure should be an identity'
        );
    }

    public function test_flatMap() {
        $failure = P\Failure(new \Exception('test'));
        $this->assertEquals(
            $failure
            , $failure->flatMap(function () {return P\Success(true);})
            , 'flatMap for failure should be an identity'
        );
    }
    public function test_flatten() {
        $failure = P\Failure(new \Exception('test'));
        $this->assertEquals(
            $failure
            , $failure->flatten()
            , 'flatten for failure should be an identity'
        );
    }

    public function test_failed() {
        $failure = P\Failure(new \Exception('test'));
        $success = $failure->failed();
        $this->assertInstanceOf(
            P\Success
            , $success
            , 'The result of failed on a failure should be a success'
        );
        $err = $success->get();
        $this->assertInstanceOf(
            '\Exception'
            , $err
            , 'The contents should be the exception.'
        );
        $this->assertEquals(
            'test'
            , $err->getMessage()
            , 'the exceptions values should be what was stored'
        );
    }

}
