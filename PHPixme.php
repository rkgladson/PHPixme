<?php
namespace PHPixme;
    /**
     * Created by PhpStorm.
     * User: rgladson
     * Date: 12/24/2015
     * Time: 12:11 PM
     */

// -- curry --
$curry = __curry(2, 'PHPixme\__curry');
const curry = '\PHPixme\curry';
/**
 * @param int $arity
 * @param callable = $hof
 * @return callable
 */
function curry($arity, $hof = null)
{
    global $curry;
    return call_user_func_array($curry, func_get_args());
}

// == curry ==

// -- nAry --
$nAry = __curry(2, function ($number = 0, $hof = null) {
    __assertPositiveOrZero($number);
    __assertCallable($hof);
    return function () use (&$number, &$hof) {
        $args = func_get_args();
        return call_user_func_array($hof, array_slice($args, 0, $number));
    };
});
const nAry = 'PHPixme\nAry';
/**
 * @param int $arity
 * @param callable = $hof
 * @return callable
 */
function nAry($arity, $hof = null)
{
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
    return __curry(3, function ($x, $y, $z) use (&$hof) {
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
    return __curry(2, function ($arg0, $arg1, ...$args) use (&$hof) {
        array_unshift($args, $arg0);
        array_unshift($args, $arg1);
        return call_user_func_array($hof, $args);
    });
}

;
// == flip ==

// -- combine --
$combine = __curry(2, function ($x, $y) {
    __assertCallable($x);
    __assertCallable($y);
    return function ($z) use (&$x, &$y) {
        $step1 = $y($z);
        return $x($step1);
    };
});
const combine = 'PHPixme\combine';
/**
 * @param callable $hofSecond
 * @param callable = $hofFirst
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
$S = __curry(3, function ($x, $y, $z) {
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
 * @param callable = $y
 * @param mixed = $z
 * @return callable|mixed
 * @sig x, y, z -> x(z)(y(z)
 */
function S($x, $y = null, $z = null)
{
    global $S;
    return call_user_func_array($S, func_get_args());
}

// == Starling ==

// -- fold --
$fold = __curry(3, function ($hof, $startVal, $arrayLike) {
    __assertCallable($hof);
    if ($arrayLike instanceof naturalTransformation) {
        return $arrayLike->fold($hof, $startVal);
    }
    __assertTraversable($arrayLike);
    $output = $startVal;
    foreach ($arrayLike as $key => $value) {
        $output = $hof($output, $value, $key, $arrayLike);
    }
    return $output;
});
const fold = 'PHPixme\fold';
/**
 * @param callable $hof
 * @param mixed = $startVal
 * @param \Traversable= $traversable
 * @return callable|mixed
 */
function fold($hof, $startVal = null, $traversable = null)
{
    global $fold;
    return call_user_func_array($fold, func_get_args());
}

// == fold ==

// -- reduce --
$reduce = __curry(2, function ($hof, $arrayLike) {
    __assertCallable($hof);
    if ($arrayLike instanceof naturalTransformation) {
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
const reduce = 'PHPixme\reduce';
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return callable|mixed
 */
function reduce($hof, $traversable = null)
{
    global $reduce;
    return call_user_func_array($reduce, func_get_args());
}

// == reduce ==

// -- map --
$map = $curry(2, function (callable $hof, $traversable) {

    // Reflect on natural transformations
    if ($traversable instanceof naturalTransformation) {
        return $traversable->map($hof);
    }
    __assertTraversable($traversable);
    $output = [];
    foreach($traversable as $key => $value) {
        $output[$key] = $hof($value, $key, $traversable);
    }
    return $output;
});
const map = 'PHPixme\reduce';
function map()
{
    global $map;
    return $map();
}

// == map ==


interface naturalTransformation
{
    static function from($traversable);
    static function of(...$traversable);

    public function fold(callable $hof, $startVal);

    public function reduce(callable $hof);

    public function map(callable $hof);

    public function filter(callable $hof);

    public function walk(callable $hof);

    public function union(...$traversableR);

    public function toArray();

    public function isEmpty();
    public function find(callable $hof);
}

const Maybe = 'PHPixme\Maybe';
function Maybe($x)
{
    return (!isset($x) || is_null($x) || is_array($x) && count($x) === 0) ? None() : Some($x);
}

abstract class Maybe implements naturalTransformation
{
    static function of(...$args)
    {
        return Maybe($args[0]);
    }

    static function from($arg)
    {
        return Maybe($arg);
    }

    abstract function isEmpty();
    public function toSeq() {
        return Seq($this->toArray());
    }
    public function getOrElse()
    {
        return $this->isEmpty() ? $this : None();
    }
}


function Some($x)
{
    return new Some($x);
}

class Some extends Maybe
{
    protected $x;

    public function __construct($x)
    {
        $this->x = $x;
    }

    public function isEmpty()
    {
        return false;
    }

    public function get()
    {
        return $this->x;
    }
    public function toArray()
    {
        return [$this->x];
    }
    public function union(...$traversableR)
    {
        // TODO: Implement union() method.
    }
    public function filter(callable $hof)
    {
        // TODO: Implement filter() method.
    }
    public function reduce(callable $hof)
    {
        // TODO: Implement reduce() method.
    }
    public function map(callable $hof)
    {
        // TODO: Implement map() method.
    }
    public function fold(callable $hof, $startVal)
    {
        // TODO: Implement fold() method.
    }
    public function find(callable $hof)
    {
        // TODO: Implement find() method.
    }
    public function walk(callable $hof)
    {
        // TODO: Implement walk() method.
    }
}

const None = 'PHPixme\None';
$none = null;
function None()
{
    global $none;
    if (is_null($none)) {
        $none = new None();
    }
    return $none;
}

class None extends Maybe
{
    static function of(...$args)
    {
        return None();
    }

    static function from($args)
    {
        return None();
    }

    public function walk(callable $hof)
    {
        return None();
    }

    public function map(callable $hof)
    {
        return None();
    }

    public function reduce(callable $hof)
    {
        throw new \InvalidArgumentException('Cannot reduce on None. Behaviour is undefined');
    }

    public function fold(callable $hof, $startVal)
    {
        return $startVal;
    }
    public function filter(callable $hof)
    {
        // TODO: Implement filter() method.
    }

    public function find(callable $hof)
    {
        return true;
    }

    public function union(...$traversableR)
    {
        // TODO: Implement union() method.
    }

    public function toArray()
    {
        return [];
    }

    public function get()
    {
        throw new \Exception('Cannot get on None!');
    }

    public function isEmpty()
    {
        return true;
    }
}

const Seq = 'PHPixme\Seq';
function Seq(&$arrayLike)
{
    return new Seq($arrayLike);
}

class Seq extends \ArrayIterator implements naturalTransformation
{
    private $array = [];

    /**
     * Seq constructor.
     * @param \Traversable|array|\PHPixme\naturalTransformation $arrayLike
     */
    function __construct($arrayLike)
    {
        if ($arrayLike instanceof naturalTransformation) {
            $this->array = $arrayLike->toArray();
        }
        __assertTraversable($arrayLike);
        if (is_array($arrayLike)) {
            $this->array = $arrayLike;
        } else {
            foreach ($arrayLike as $key => $value) {
                $this->array[$key] = $value;
            }
        }
        parent::__construct($this->array);
    }

    /**
     * Calls the constructor with the array like parameter
     * @param $arrayLike
     * @return Seq
     */
    static function from($arrayLike)
    {
        return new Seq($arrayLike);
    }

    static function of(...$args)
    {
        return new Seq($args);
    }

    public function __invoke($index)
    {
        return $this->array[$index];
    }

    public function map(callable $hof)
    {
        $output = [];
        foreach ($this->array as $key => $value) {
            $output[$key] = $hof($value, $key, $this);
        }
        return $this::from($output);
    }

    public function filter(callable $hof)
    {
        $output = [];
        foreach ($this->array as $key => $value) {
            if ($hof($value, $key, $this)) {
                $output[$key] = $value;
            }
        }
        return $this::from($output);
    }

    public function fold(callable $hof, $startVal)
    {
        $output = $startVal;
        foreach ($this->array as $key => $value) {
            $output = $hof($output, $value, $key, $this);
        }
        return $output;
    }

    public function reduce(callable $hof)
    {
        if (($length = count($this->array)) < 1) {
            throw new \OutOfRangeException('Cannot reduce a set of 0, this behavior is undefined');
        }
        $keyR = array_keys($this->array);
        $output = $this->array[array_shift($keyR)];
        foreach ($keyR as $key) {
            $output = $hof($output, $this->array[$key], $key, $this);
        }
        return $output;
    }

    public function union(...$arrayLikeN)
    {
        $output = $this->array;
        foreach ($arrayLikeN as $arrayLike) {
            __assertTraversable($arrayLike);
            foreach ($arrayLike as $key => $value) {
                $output[$key] = $value;
            }
        }
        return $this::from($output);
    }

    public function find(callable $hof)
    {
        $found = null;
        foreach (array_keys($this->array) as $key) {

            if ($hof($this->array[$key], $key, $this)) {
                $found = $this->array[$key];
                break;
            }
        }
        return Maybe($found);
    }

    public function walk(callable $hof)
    {
        foreach (array_keys($this->array) as $key) {
            $hof($this->array[$key], $key, $this);
        }
    }

    public function head()
    {
        return $this->array[array_keys($this->array)[0]];
    }

    public function tail()
    {
        if (count($this->array) > 1) {
            $tail = $this->array;
            array_shift($tail);
            return $this::from($tail);
        }
        return $this::from([]);
    }

    public function indexOf($thing)
    {
        $key = array_search($thing, $this->array, true);
        return $key === false ? -1 : $key;
    }

    public function partition($hof)
    {
        __assertCallable($hof);
        $output = [/*false =>*/
            [],/*true =>*/
            []];
        foreach ($this->array as $key => $value) {
            $output[(boolean)$hof($value, $key, $this) ? 1 : 0][$key] = $value;
        }
        return $this::from($output);
    }

    public function group($hof)
    {
        __assertCallable($hof);
        $output = [];
        foreach ($this->array as $key => $value) {
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

    public function dropRight($number = 0)
    {
        return $this::from(array_slice($this->array, 0, -1 * $number, true));
    }

    public function take($number = 0)
    {
        return $this::from(array_slice($this->array, 0, $number, true));
    }

    public function takeRight($number = 0)
    {
        return $this::from(array_slice($this->array, -1 * $number, null, true));
    }

    public function toArray()
    {
        return $this->array;
    }

    public function isEmpty()
    {
        return empty($this->array);
    }

    public function toString($glue = ',') {
        return implode($glue, $this->array);
    }
    public function toJson() {
        return json_encode($this->array);
    }
    public function reverse() {
        return $this::from(array_reverse($this->array, true));
    }

}


// -- Internal functions --
function __assertCallable(&$callable)
{
    if (!is_callable($callable)) {
        throw new \InvalidArgumentException('callback must be a callable function');
    }
}

function __assertPositiveOrZero($number)
{
    if (!is_integer($number) || $number < 0) {
        throw new \InvalidArgumentException('argument must be a integer 0 or greater');
    }
}

function __assertTraversable(&$arrayLike)
{
    if (!is_array($arrayLike) && !($arrayLike instanceof \Traversable)) {
        throw new \InvalidArgumentException('argument must be a Traversable or array');
    }
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
