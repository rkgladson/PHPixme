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
            , 'Failure->isFailure should be true'
        );
        $this->assertFalse(
            $failure->isSuccess()
            , 'Failure->isSuccess should be false'
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

    /**
     * Assure the contract of Failure->orElse is maintained
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


    public function test_recover_callback ()
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
     * @expectedException \Exception
     */
    public function test_recoverWith_contract_broken()
    {
        P\Failure(new \Exception('test'))
            ->recoverWith(function () {
                return '^_^';
            });
    }

    public function test_recoverWith_success($value = true) {
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
    public function test_recoverWith_failure() {
        $failure = P\Failure(new \Exception('^_^'));
        $failRecover = function () use ($failure) {
            return $failure;
        };
        $results = P\Failure(new \Exception('Test'))->recoverWith($failRecover);
        $this->assertTrue(
            $results === $failure
            , 'Failure->recoverWith should be able to recoverWith a Failure value'
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
            $err === $result['failure']
            , 'Failure->toArray should return contain "failure"=>Exception'
        );
        $this->assertNotTrue(
            isset($result['success'])
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

    public function test_transform_scenario_to_success($value = 'test')
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
            throw new \Exception('Failure->walk callback should not be run!');
        };
        P\Failure(new \Exception())->walk($notRun);
    }
}
