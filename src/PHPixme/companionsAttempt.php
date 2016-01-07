<?php
namespace PHPixme;
const Attempt = 'PHPixme\Attempt';

/**
 * @param callable $hof
 * @return Success|Failure
 */
function Attempt(callable $hof)
{
    try {
        return Success($hof());
    } catch (\Exception $e) {
        return Failure($e);
    }
}

const Success = 'PHPixme\Success';
/**
 * @param $value
 * @return Success
 */
function Success($value)
{
    return new Success($value);
}


const Failure = 'PHPixme\Failure';
/**
 * @param $exception - The failure value
 * @return Failure
 */
function Failure ($exception) {
    return new Failure($exception);
}