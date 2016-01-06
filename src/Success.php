<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:09 AM
 */

namespace PHPixme;

const Success = 'PHPixme\Success';
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
        // TODO: Implement get() method.
    }

    public function filter(callable $hof)
    {
        // TODO: Implement filter() method.
    }

    public function flatMap(callable $hof)
    {
        // TODO: Implement flatMap() method.
    }

    public function flatten()
    {
        // TODO: Implement flatten() method.
    }

    public function failed()
    {
        // TODO: Implement failed() method.
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
        // TODO: Implement map() method.
    }

    public function recover(callable $rescueException)
    {
        // TODO: Implement recover() method.
    }

    public function recoverWith(callable $hof)
    {
        // TODO: Implement recoverWith() method.
    }

    public function transform(callable $success, callable $failure)
    {
        // TODO: Implement transform() method.
    }

    public function walk(callable $hof)
    {
        // TODO: Implement walk() method.
    }
}