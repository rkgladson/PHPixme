<?php
namespace PHPixme;
//Fixme: figure out a way to get auto-loader to let me pass in my namespace level function instances
global $__PHPIXME_NAMESPACE;
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 12/24/2015
 * Time: 12:11 PM
 */

// -- curry --

const curry = '\PHPixme\curry';
$__PHPIXME_NAMESPACE[curry] = __curry(2, 'PHPixme\__curry');
/**
 * @param int $arity
 * @param callable = $hof
 * @return callable
 */
function curry($arity, $hof = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[curry], func_get_args());
}

// == curry ==

// -- nAry --
const nAry = 'PHPixme\nAry';
$__PHPIXME_NAMESPACE[nAry] = __curry(2, function ($number = 0, $hof = null) {
    __assertPositiveOrZero($number);
    __assertCallable($hof);
    return function () use (&$number, &$hof) {
        $args = func_get_args();
        return call_user_func_array($hof, array_slice($args, 0, $number));
    };
});
/**
 * @param int $arity
 * @param callable = $hof
 * @return callable
 */
function nAry($arity, $hof = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[nAry], func_get_args());
}

// == nAry ==

// -- unary --
const unary = 'PHPixme\unary';
/**
 * @param callable $hof
 * @return callable
 */
function unary($hof)
{
    __assertCallable($hof);
    return function ($arg) use (&$hof) {
        return $hof($arg);
    };
}

// == unary ==

// -- binary --
function binary($hof)
{
    __assertCallable($hof);
    return __curry(2, function ($x, $y) use (&$hof) {
        return $hof($x, $y);
    });
}

// == binary ==
// -- ternary --
const ternary = 'PHPixme\ternary';
function ternary($hof)
{
    __assertCallable($hof);
    return __curry(3, function ($x, $y, $z) use ($hof) {
        return $hof($x, $y, $z);
    });
}

// == ternary ==


// -- flip --
const flip = 'PHPixme\flip';
/**
 * @param $hof
 * @return callable
 */
function flip($hof)
{
    __assertCallable($hof);
    return __curry(2, function (...$args) use ($hof) {
        $temp = $args[0];
        $args[0] = $args[1];
        $args[1] = $temp;
        return call_user_func_array($hof, $args);
    });
}

;
// == flip ==

// -- combine --
const combine = 'PHPixme\combine';
$__PHPIXME_NAMESPACE[combine] = __curry(2, function ($x, $y) {
    __assertCallable($x);
    __assertCallable($y);
    return function ($z) use (&$x, &$y) {
        $step1 = $y($z);
        return $x($step1);
    };
});
/**
 * @param callable $hofSecond
 * @param callable = $hofFirst
 * @return callable
 */
function combine($hofSecond, $hofFirst)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[combine], func_get_args());
}

// == combine ==

// -- Kestrel --
const K = 'PHPixme\K';
/**
 * @param mixed $first
 * @return callable
 * @sig first -> ignored -> first
 */
function K($first)
{
    return function () use (&$first) {
        return $first;
    };

}

// == Kestrel ==

// -- Kite --
const KI = 'PHPixme\KI';
/**
 * @return callable
 * @sig ignored -> second -> second
 */
function KI()
{
    return unary(I);
}

// == Kite ==

// -- Idiot --
const I = 'PHPixme\I';
/**
 * @param mixed $x
 * @return mixed $x
 * @sig x -> x
 */
function I($x)
{
    return $x;
}

// == Idiot ==

// -- Starling --
const S = 'PHPixme\S';
$__PHPIXME_NAMESPACE[S] = __curry(3, function ($x, $y, $z) {
    __assertCallable($x);
    __assertCallable($y);
    $step1 = $y($z);
    $step2 = $x($z);
    __assertCallable($step2);
    return $step2($step1);
});
/**
 * @param callable $x
 * @param callable = $y
 * @param mixed = $z
 * @return callable|mixed
 * @sig x, y, z -> x(z)(y(z)
 */
function S($x, $y = null, $z = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[S], func_get_args());
}

// == Starling ==

// -- fold --
const fold = 'PHPixme\fold';
$__PHPIXME_NAMESPACE[fold] = __curry(3, function ($hof, $startVal, $arrayLike) {
    __assertCallable($hof);
    if ($arrayLike instanceof NaturalTransformationInterface) {
        return $arrayLike->fold($hof, $startVal);
    }
    __assertTraversable($arrayLike);
    $output = $startVal;
    foreach ($arrayLike as $key => $value) {
        $output = $hof($output, $value, $key, $arrayLike);
    }
    return $output;
});
/**
 * @param callable $hof
 * @param mixed = $startVal
 * @param \Traversable= $traversable
 * @return callable|mixed
 */
function fold($hof, $startVal = null, $traversable = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[fold], func_get_args());
}

// == fold ==

// -- reduce --
const reduce = 'PHPixme\reduce';
$__PHPIXME_NAMESPACE[reduce] = __curry(2, function ($hof, $arrayLike) {
    __assertCallable($hof);
    if ($arrayLike instanceof NaturalTransformationInterface) {
        return $arrayLike->reduce($hof);
    }
    __assertTraversable($arrayLike);
    $iter = is_array($arrayLike) ? new \ArrayIterator($arrayLike) : $arrayLike;
    $iter->rewind();
    if (!$iter->valid()) {
        throw new \InvalidArgumentException('Cannot reduce on collection of less than one. Behaviour is undefined');
    }
    $output = $iter->current();
    $iter->next();
    while ($iter->valid()) {
        $output = $hof($output, $iter->current(), $iter->key(), $arrayLike);
        $iter->next();
    }
    return $output;
});
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return callable|mixed
 */
function reduce($hof, $traversable = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[reduce], func_get_args());
}

// == reduce ==

// -- map --
const map = 'PHPixme\map';
$__PHPIXME_NAMESPACE[map] = curry(2, function (callable $hof, $traversable) {

    // Reflect on natural transformations
    if ($traversable instanceof NaturalTransformationInterface) {
        return $traversable->map($hof);
    }
    __assertTraversable($traversable);
    $output = [];
    foreach ($traversable as $key => $value) {
        $output[$key] = $hof($value, $key, $traversable);
    }
    return $output;
});
function map(callable $hof, $traversable = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[map], func_get_args());
}

// == map ==


// -- Internal functions --
function __assertCallable($callable)
{
    if (!is_callable($callable)) {
        throw new \InvalidArgumentException('callback must be a callable function');
    }
    return $callable;
}

function __assertPositiveOrZero($number)
{
    if (!is_integer($number) || $number < 0) {
        throw new \InvalidArgumentException('argument must be a integer 0 or greater');
    }
    return $number;
}

function __assertTraversable($arrayLike)
{
    if (!is_array($arrayLike) && !($arrayLike instanceof \Traversable)) {
        throw new \InvalidArgumentException('argument must be a Traversable or array');
    }
    return $arrayLike;
}

function __curryGiven($prevArgs, &$arity, &$callable)
{
    return function (...$newArgs) use ($arity, $callable, $prevArgs) {
        $args = array_merge($prevArgs, $newArgs);
        if (count($args) >= $arity) {
            return call_user_func_array($callable, $args);
        }
        return __curryGiven($args, $arity, $callable);
    };
}

;

function __curry($arity = 0, $callable)
{
    __assertPositiveOrZero($arity);
    __assertCallable($callable);

    return __curryGiven([], $arity, $callable);
}
