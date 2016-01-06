<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:09 AM
 */

namespace PHPixme;

function Failure ($exception) {
    return new Failure($exception);
}

class Failure extends Attempt
{
    private $err;

    public function get()
    {
        throw $this->err;
    }

    public function filter(callable $hof)
    {
        return $this;
    }

    public function flatMap(callable $hof)
    {
        return $this;
    }

    public function flatten()
    {
        return $this;
    }

    public function failed()
    {
        return Success($this->err);
    }

    function isFailure()
    {
        return true;
    }

    function isSuccess()
    {
        return false;
    }

    public function map(callable $hof)
    {
        return $this;
    }

    public function recover(callable $rescueException)
    {
        return Attempt(function () use ($rescueException) {
            return $rescueException($this->err);
        });
    }

    public function recoverWith(callable $hof)
    {
        try {
            $result = $hof($this->err);
        } catch (\Exception $e) {
            return Failure($e);
        }
        return __assertAttemptType($result);
    }

    public function transform(callable $success, callable $failure)
    {
        try {
            $result = $failure($this->err);
        } catch (\Exception $e) {
            return Failure($e);
        }
        return __assertAttemptType($result);
    }

    public function walk(callable $hof)
    {
        // This space is intentionally left blank.
    }

    public function __construct(\Exception $exception)
    {
        $this->err = $exception;
    }

}