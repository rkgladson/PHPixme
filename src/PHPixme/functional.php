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

const curry = __NAMESPACE__ . '\curry';
$__PHPIXME_NAMESPACE[curry] = __curry(2, __NAMESPACE__ . '\__curry');
/**
 * Take a callable and produce a curried \Closure
 * @param int $arity
 * @param callable = $hof
 * @return \Closure
 */
function curry($arity, $hof = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[curry], func_get_args());
}

// == curry ==

// -- nAry --
const nAry = __NAMESPACE__ . '\nAry';
$__PHPIXME_NAMESPACE[nAry] = __curry(2, function ($number = 0, $hof = null) {
    __assertPositiveOrZero($number);
    __assertCallable($hof);
    return function () use (&$number, &$hof) {
        $args = func_get_args();
        return call_user_func_array($hof, array_slice($args, 0, $number));
    };
});
/**
 * Wrap a function in an argument that will eat all but n arguments
 * @param int $arity
 * @param callable = $hof
 * @return \Closure
 */
function nAry($arity, $hof = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[nAry], func_get_args());
}

// == nAry ==

// -- unary --
const unary = __NAMESPACE__ . '\unary';
/**
 * wrap a callable in a function that will eat but one argument
 * @param callable $hof
 * @return \Closure
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
const binary = __NAMESPACE__ . '\binary';
/**
 * Wrap a callable in a function that will eat all but two arguments
 * @param callable $hof
 * @return \Closure
 */
function binary($hof)
{
    __assertCallable($hof);
    return __curry(2, function ($x, $y) use (&$hof) {
        return $hof($x, $y);
    });
}

// == binary ==
// -- ternary --
const ternary = __NAMESPACE__ . '\ternary';
/**
 * Wrap a callable function in one that will eat all but three arguments
 * @param callable $hof
 * @return \Closure
 */
function ternary($hof)
{
    __assertCallable($hof);
    return __curry(3, function ($x, $y, $z) use ($hof) {
        return $hof($x, $y, $z);
    });
}

// == ternary ==
// -- nullary --
const nullary = __NAMESPACE__ . '\nullary';
/**
 * Wrap a function in one that will eat all arguments
 * @param $hof
 * @return \Closure
 */
function nullary($hof)
{
    __assertCallable($hof);
    return function () use ($hof) {
        return $hof();
    };
}

// == nullary ==

// -- flip --
const flip = __NAMESPACE__ . '\flip';
/**
 * Takes a callable, then flips the two next arguments before calling tthe function
 * @sig f(a, b, ....z) -> f(b,a, ... z)
 * @param callable $hof
 * @return \Closure
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
const combine = __NAMESPACE__ . '\combine';
$__PHPIXME_NAMESPACE[combine] = __curry(2, function ($x, $y) {
    __assertCallable($x);
    __assertCallable($y);
    return function ($z) use ($x, $y) {
        $step1 = $y($z);
        return $x($step1);
    };
});
/**
 * Takes two functions and has the first consume the output of the second, combining them to a single function
 * @sig x y z -> x(y(z))
 * @param callable $hofSecond
 * @param callable = $hofFirst
 * @return \Closure
 */
function combine($hofSecond, $hofFirst = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[combine], func_get_args());
}

// == combine ==

// -- Kestrel --
const K = __NAMESPACE__ . '\K';
/**
 * @param mixed $first
 * @return \Closure
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
const KI = __NAMESPACE__ . '\KI';
/**
 * @param $ignored= This parameter will be ignored
 * @return \Closure
 * @sig ignored -> second -> second
 */
function KI($ignored = null)
{
    return unary(I);
}

// == Kite ==

// -- Idiot --
const I = __NAMESPACE__ . '\I';
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
const S = __NAMESPACE__ . '\S';
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
 * @return \Closure|mixed
 * @sig x, y, z -> x(z)(y(z)
 */
function S($x, $y = null, $z = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[S], func_get_args());
}

// == Starling ==

// -- fold --
const fold = __NAMESPACE__ . '\fold';
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
 * @return \Closure|mixed
 */
function fold($hof, $startVal = null, $traversable = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[fold], func_get_args());
}

// == fold ==

// -- reduce --
const reduce = __NAMESPACE__ . '\reduce';
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
 * @return \Closure|mixed
 */
function reduce($hof, $traversable = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[reduce], func_get_args());
}

// == reduce ==

// -- map --
const map = __NAMESPACE__ . '\map';
$__PHPIXME_NAMESPACE[map] = __curry(2, function (callable $hof, $traversable) {

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
/**
 * @param callable $hof
 * @param array|\Traversable|\PHPixme\NaturalTransformationInterface $traversable
 * @return \Closure|mixed
 */
function map(callable $hof, $traversable = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[map], func_get_args());
}

// == map ==

// -- callWith --
const callWith = __NAMESPACE__ . '\callWith';
$__PHPIXME_NAMESPACE[callWith] = __curry(2, function ($accessor, $container) {
    $callable = is_array($container) ?
        (isset($container[$accessor]) ? $container[$accessor] : null)
        : [$container, $accessor];
    __assertCallable($callable);
    return function () use (&$callable) {
        return call_user_func_array($callable, func_get_args());
    };
});
/**
 * Produce a function that calls a function within a array or object
 * @param string $accessor
 * @param object|array $container =
 * @return \Closure ($container) -> ((args) -> $container{[$accessor]}(...args))
 */
function callWith($accessor, $container = null)
{
    global $__PHPIXME_NAMESPACE;
    return call_user_func_array($__PHPIXME_NAMESPACE[callWith], func_get_args());
}

// == callWith ==

// -- pluckObjectWith --
const pluckObjectWith = __NAMESPACE__ . '\pluckObjectWith';
/**
 * Creates a function to access the property of an object
 * @param string $accessor
 * @return \Closure ($object) -> object->accessor
 */
function pluckObjectWith($accessor)
{
    return function ($container) use ($accessor) {
        return $container->{$accessor};
    };
}

// == pluckObjectWith ==
// -- pluckArrayWith --
const pluckArrayWith = __NAMESPACE__ . '\pluckArrayWith';
/**
 * Creates a function to access the property of an object
 * @param string $accessor
 * @return \Closure ($object) -> object->accessor
 */
function pluckArrayWith($accessor)
{
    return function ($container) use ($accessor) {
        return $container[$accessor];
    };
}

// == pluckArrayWith ==

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


/**
 * Uncurried curry function for internal use
 * @param int $arity
 * @param callable $callable
 * @return \Closure
 */
function __curry($arity = 0, callable $callable)
{
    __assertPositiveOrZero($arity);
    __assertCallable($callable);

    return __curryGiven([], $arity, $callable);
}
