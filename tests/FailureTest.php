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

    public function test_is_status()
    {
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

    public function test_getOrElse($value = 10)
    {
        $this->assertTrue(
            $value === (P\Failure(new \Exception('Test'))->getOrElse(10))
            , 'getOrElse on failure should always return the default value'
        );
    }


    public function test_orElse_complaint($value = '$yay')
    {
        $successV = P\Success($value);
        $failureV = P\Failure(new \Exception($value));
        $failure = P\Failure(new \Exception('Test'));
        $result = $failure->orElse(function () use ($successV) {
            return $successV;
        });
        $this->assertInstanceOf(
            P\Success
            , $result
            , 'it should of subsisted with success'
        );
        $this->assertTrue(
            $successV === $result
            , 'it should contain the data we defined'
        );
        $result = $failure->orElse(function () use ($failureV) {
            return $failureV;
        });
        $this->assertInstanceOf(
            P\Failure
            , $result
            , 'Failure->orElse should of contained the failure we substituted'
        );
        $this->assertTrue(
            $failureV === $result
            , 'The failure passed in also should be the default for Failure->orElse'
        );
    }

    /**
     * Assure the contract is maintained
     * @expectedException \Exception
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
            , 'filter for failure should be an identity'
        );
    }

    public function test_flatMap()
    {
        $failure = P\Failure(new \Exception('test'));
        $this->assertTrue(
            $failure === ($failure->flatMap(function () {
                return P\Success(true);
            }))
            , 'flatMap for failure should be an identity'
        );
    }

    public function test_flatten()
    {
        $failure = P\Failure(new \Exception('test'));
        $this->assertTrue(
            $failure === ($failure->flatten())
            , 'flatten for failure should be an identity'
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
            , 'The result of failed on a failure should be a success'
        );
        $this->assertTrue(
            $origErr === ($success->get())
            , 'The result of the Success produced by failed should be our origonal error'
        );
    }

    public function test_map()
    {
        $failure = P\Failure(new \Exception());
        $this->assertTrue(
            $failure === ($failure->map(function () {
                return 1;
            }))
            , 'map for failure should be an identity'
        );
    }

    /**
     * @depends test_failed
     */
    public function test_recover()
    {
        $exc = new \Exception('test');
        $failure = P\Failure($exc);

        $results = $failure->recover(function () {
            throw new \Exception('^_^');
        });

        $failure->recover(function () use ($exc, $failure) {
            $this->assertTrue(
                2 === func_num_args()
                , 'It should be passed two arguments'
            );
            $this->assertTrue(
                $exc === func_get_arg(0)
                , 'The value should be equal to what the Failure contains'
            );
            $this->assertTrue(
                $failure === func_get_arg(1)
                , 'The container should be equal to the Failure being operating on'
            );
            return $exc;
        });
        $this->assertInstanceOf(
            P\Failure
            , $results
            , 'the recovery should be able to fail even when throwing an exception.'
        );
        $this->assertTrue(
            '^_^' === ($results->failed()->get()->getMessage())
            , 'the recovery value should be what was sent it.'
        );
        $results = $failure->recover(function () {
            return true;
        });
        $this->assertInstanceOf(
            P\Success
            , $results
            , 'A non-thrown environment should be a success'
        );
        $this->assertTrue(
            $results->get()
            , 'The value of a successful recovery should be what was sent'
        );
    }

    /**
     * Ensure the contract is maintained that if the type is broken, it throws an exception
     * @expectedException \Exception
     */
    public function test_recoverWith_contract_broken()
    {
        P\Failure(new \Exception('test'))
            ->recoverWith(function () {
                return '^_^';
            });
    }

    public function test_toArray()
    {
        $err = new \Exception('test');
        $result = P\Failure($err)->toArray();
        $this->assertTrue(
            $err === $result['failure']
            , 'failure\s toArray method should return an array with the exception in it at key "failure"'
        );
        $this->assertNotTrue(
            isset($result['success'])
            , 'It should not contain a success key'
        );
    }

    public function test_toMaybe()
    {
        $this->assertInstanceOf(
            P\None
            , P\Failure(new \Exception('test'))->toMaybe()
            , 'Failure should transform to None'
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
                , 'The callback should be passed two arguments'
            );
            $this->assertTrue(
                $exc === func_get_arg(0)
                , 'The value passed should be What failure contains'
            );
            $this->assertTrue(
                $fail === func_get_arg(1)
                , 'The container should be the Failure instance itself'
            );
            return $fail;
        });
    }

    public function test_transform($value = 'test')
    {
        $fail = P\Failure(new \Exception($value));
        $this->assertTrue(
            $value === ($fail->transform(
                function () {
                },
                function ($value) {
                    return P\Success($value->getMessage());
                }
            )->get())
            , 'it should be able to transform one type into another'
        );
    }

    /**
     * @expectedException \Exception
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
            throw new \Exception('This should not be run!');
        };
        P\Failure(new \Exception())->walk($notRun);
    }
}
