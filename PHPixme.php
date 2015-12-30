<?php
namespace PHPixme;
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 12/24/2015
 * Time: 12:11 PM
 */

// -- curry --
$curry = __curry(2,'PHPixme\__curry');
const curry = '\PHPixme\curry';
/**
 * @param int $arity
 * @param callable= $hof
 * @return callable
 */
function curry ($arity, $hof = null) {
    global $curry;
    return call_user_func_array($curry, func_get_args());
}
// == curry ==

// -- nAry --
$nAry = __curry(2, function ($number = 0, $hof = null)
{
    __assertPositiveOrZero($number);
    __assertCallable($hof);
    return function () use (&$number, &$hof)
    {
        $args = func_get_args();
        return call_user_func_array($hof, array_slice($args, 0, $number));
    };
});
const nAry = 'PHPixme\nAry';
/**
 * @param int $arity
 * @param callable= $hof
 * @return callable
 */
function nAry ($arity, $hof = null) {
    global $nAry;
    return call_user_func_array($nAry, func_get_args());
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
    return function($arg) use (&$hof)
    {
        return $hof($arg);
    };
}
// == unary ==

// -- flip --
const flip = 'PHPixme\flip';
/**
 * @param $hof
 * @return callable
 */
function flip($hof)
{
    __assertCallable($hof);
    return function (...$args) use (&$hof) {
        $temp = $args[0]; $args[0] = $args[1]; $args[1] = $temp;
        return call_user_func_array($hof, $args);
    };
};
// == flip ==

// -- combine --
$combine = __curry(2, function ($x, $y)
{
    __assertCallable($x);
    __assertCallable($y);
    return function ($z) use (&$x, &$y)
    {
        $step1 = $y($z);
        return $x($step1);
    };
});
const combine = 'PHPixme\combine';
/**
 * @param callable $hofSecond
 * @param callable= $hofFirst
 * @return callable
 */
function combine($hofSecond, $hofFirst)
{
    global $combine;
    return call_user_func_array($combine, func_get_args());
}
// == combine ==

// -- Kestrel --
const K = 'PHPixme\K';
/**
 * @param mixed $first
 * @return callable
 * @sig first -> ignored -> first
 */
function K ($first) {
    return function () use (&$first)
    {
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
function KI ()
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
function I ($x)
{
    return $x;
}
// == Idiot ==

// -- Starling --
$S = __curry(3, function ($x, $y, $z)
{
    __assertCallable($x);
    __assertCallable($y);
    $step1 = $y($z);
    $step2 = $x($z);
    __assertCallable($step2);
    return $step2($step1);
});
const S = 'PHPixme\S';
/**
 * @param callable $x
 * @param callable= $y
 * @param mixed= $z
 * @return callable|mixed
 * @sig x, y, z -> x(z)(y(z)
 */
function S ($x, $y = null, $z = null)
{
    global $S;
    return call_user_func_array($S, func_get_args());
}
// == Starling ==

// -- fold --
$fold = __curry(3, function ($hof, $startVal, $iterable)
{
    __assertCallable($hof);
    __assertTraversable($iterable);
    $output = $startVal;
    foreach($iterable as $key => $value) {
        $output = $hof($output, $value, $key, $iterable);
    }
    return  $output;
});
const fold = 'PHPixme\fold';
/**
 * @param callable $hof
 * @param mixed= $tartVal
 * @param \Traversable= $traversable
 * @return callable|mixed
 */
function fold ($hof, $tartVal = null, $traversable = null)
{
    global $fold;
    return call_user_func_array($fold, func_get_args());
}
// == fold ==

// -- reduce --
$reduce = __curry(2, function ($hof, $arrayLike)
{
    __assertCallable($hof);
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
    return  $output;
});
const reduce = 'PHPixme\reduce';
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return callable|mixed
 */
function reduce ($hof, $traversable = null)
{
    global $reduce;
    return call_user_func_array($reduce, func_get_args());
}
// == reduce ==




class Seq extends \ArrayIterator{
    private $array = [];
    function __construct(array $arrayLike)
    {
        __assertTraversable($arrayLike);
        if (is_array($arrayLike)) {
            $this->array = $arrayLike;
        } else {
            foreach($arrayLike as $key => $value) {
                $this->array[$key] = $value;
            }
        }
        parent::__construct($this->array);
    }

    static function from($arrayLike) {
        return new Seq($arrayLike);
    }

    static function of(...$args) {
        return new Seq($args);
    }

    public function map($hof) {
        __assertCallable($hof);
        $output = [];
        foreach ($this->array as $key => $value) {
            $output[$key] = $hof($value, $key, $this);
        }
        return $this::from($output);
    }
    public function filter ($hof) {
        __assertCallable($hof);
        $output = [];
        foreach($this->array as $key => $value) {
            if ($hof($value, $key, $this)) {
                $output[$key] = $value;
            }
        }
        return $this::from($output);
    }
    public function fold($hof, $startVal) {
        __assertCallable($hof);
        $output = $startVal;
        foreach($this->array as $key => $value) {
            $output = $hof($output, $value, $key, $this);
        }
        return $output;
    }
    public function reduce($hof) {
        __assertCallable($hof);
        if (($length = count($this->array)) < 1) {
            throw new \OutOfRangeException('Cannot reduce a set of 0, this behavior is undefined');
        }
        $keyR = array_keys($this->array);
        $output = $this->array[array_pop($keyR)];
        foreach($keyR as $key) {
            $output = $hof($output, $this->array[$key], $key, $this);
        }
        return $output;
    }
    public function concat(...$arrayLikeN) {
        $output = $this->array;
        foreach ($arrayLikeN as $arrayLike) {
            __assertTraversable($arrayLike);
            foreach($arrayLike as $key=>$value) {
                $output[$key] = $value;
            }
        }
        return $this::from($output);
    }

    public function indexOf(&$thing)
    {
        $keys = array_keys( $this->array, $thing, true);
        return $keys[0];
    }

    public function partition($hof) {
        __assertCallable($hof);
        $output = [/*false =>*/[],/*true =>*/[]];
        foreach($this->array as $key => $value) {
            $output[(boolean)$hof($value, $key, $this)?1:0][$key] = $value;
        }
        return $this::from($output);
    }
    public function group ($hof) {
        __assertCallable($hof);
        $output = [];
        foreach($this->array as $key => $value) {
            $groupKey = (string)$hof($value, $key, $this);
            if (!is_array($output[$groupKey])) {
                $output[$groupKey] = [];
            }
            $output[$groupKey][$key] = $value;
        }
        return $this::from($output);
    }
    public function drop($number = 0)
    {
        return $this::from(array_slice($this->array, $number, null, true));
    }
    public function dropRight($number = 0) {
        return $this::from(array_slice($this->array, 0, -1 * $number, true));
    }
    public function take($number = 0)
    {
        return $this::from(array_slice($this->array, 0, $number, true));
    }
    public function takeRight($number = 0) {
        return $this::from(array_slice($this->array, -1 * $number, null, true));
    }

    public function toArray()
    {
        return $this->array;
    }

}


// -- Internal functions --
function __assertCallable (&$callable) {
    if (!is_callable($callable)) {
        throw new \InvalidArgumentException('callback must be a callable function');
    }
}
function __assertPositiveOrZero ($number) {
    if (!is_integer($number) || $number < 0) {
        throw new \InvalidArgumentException('argument must be a integer 0 or greater');
    }
}
function __assertTraversable (&$arrayLike) {
    if (!is_array($arrayLike) && !($arrayLike instanceof \Traversable)) {
        throw new \InvalidArgumentException('argument must be a Traversable or array');
    }
}

function __curryGiven($prevArgs, &$arity, &$callable)
{
    return function (...$newArgs) use ($arity, $callable, $prevArgs)
    {
        $args = array_merge($prevArgs, $newArgs);
        if (count($args) >= $arity) {
            return call_user_func_array($callable, $args);
        }
        return __curryGiven($args, $arity, $callable);
    };
};

function __curry($arity = 0, $callable)
{
    __assertPositiveOrZero($arity);
    __assertCallable($callable);

    return __curryGiven([], $arity, $callable);
}
