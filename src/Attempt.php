<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:03 AM
 */

namespace PHPixme;

const Attempt = 'PHPixme\Attempt';
function Attempt(callable $hof)
{
    try {
        return Success($hof());
    } catch (\Exception $e) {
        return Failure($e);
    }
}

abstract class Attempt
{

    abstract public function isFailure();

    abstract public function isSuccess();

    abstract public function get();

    public function getOrElse($default)
    {
        return $this->isSuccess() ? $this->get() : $default;
    }

    public function orElse(callable $hof)
    {
        if ($this->isSuccess()) {
            return $this;
        }
        try {
            $result = $hof();
        } catch (\Exception $e) {
            return Failure($e);
        }
        return __assertAttemptType($result);
    }

    abstract public function filter(callable $hof);

    abstract public function flatMap(callable $hof);

    abstract public function flatten();

    abstract public function failed();

    abstract public function map(callable $hof);

    abstract public function recover(callable $rescueException);

    abstract public function recoverWith(callable $hof);

    abstract public function transform(callable $success, callable $failure);
    public function toArray() {
        try {
            return ['success'=> $this->get()];
        } catch (\Exception $e) {
            return ['failure' => $e];
        }
    }
    public function toOption()
    {
        return $this->isSuccess() ? Some($this->get()) : None();
    }

    abstract public function walk(callable $hof);
}

function __assertAttemptType($unknown) {
    if (!$unknown instanceof Attempt) {
        throw new \Exception ('return value must be an instance of Attempt!');
    }
    return $unknown;
}