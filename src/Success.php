<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:09 AM
 */

namespace PHPixme;

const Success = 'PHPixme\Success';
/**
 * @param $value
 * @return Success
 */
function Success($value)
{
    return new Success($value);
}

class Success extends Attempt
{
    private $value = null;

    static function from()
    {

    }
    static function of($args)
    {

    }
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function filter(callable $hof)
    {
        try {
            return ($hof($this->value)) ?
                $this
                : Failure(new \Exception('$value did not meet criteria.'));
        } catch (\Exception $e) {
            return Failure($e);
        }
    }

    public function flatMap(callable $hof)
    {
        try {
            $result = $hof($this->value);
        } catch (\Exception $e){
            return Failure($e);
        }
        return __assertAttemptType($result);
    }

    public function flatten()
    {
        return __assertAttemptType($this->value);
    }

    public function failed()
    {
        return Failure(new \Exception('Success.failed is an unsupported action.'));
    }

    public function isFailure()
    {
        return false;
    }

    public function isSuccess()
    {
        return true;
    }

    public function map(callable $hof)
    {
        return Some($hof($this->value, 0, $this));
    }

    public function recover(callable $rescueException)
    {
        return $this;
    }

    public function recoverWith(callable $hof)
    {
        return $this;
    }

    public function transform(callable $success, callable $failure)
    {
        try {
            $result = $success($this->value);
        } catch (\Exception $e) {
            return Failure($e);
        }
        return __assertAttemptType($result);
    }


    public function walk(callable $hof)
    {
        $hof($this->value, 0, $this);
    }
}