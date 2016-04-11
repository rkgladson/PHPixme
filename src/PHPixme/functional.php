<?php
namespace PHPixme;

// -- placeholder --
/**
 * Returns the placeholder instance that is used for placing gaps in curry
 * @return \stdClass
 */
function _ () {
  return __PRIVATE__::placeholder();
}
// == placeholder ==

// -- curry --

const curry = __NAMESPACE__ . '\curry';
__PRIVATE__::$instance[curry] = __PRIVATE__::curry(2, __NAMESPACE__ . '\__PRIVATE__::curry');
/**
 * Take a callable and produce a curried \Closure
 * @param int $arity
 * @param callable = $hof
 * @return \Closure
 */
function curry($arity, $hof = null)
{
  return call_user_func_array(__PRIVATE__::$instance[curry], func_get_args());
}

// == curry ==

// -- nAry --
const nAry = __NAMESPACE__ . '\nAry';
__PRIVATE__::$instance[nAry] = __PRIVATE__::curry(2, function ($number = 0, $hof = null) {
  __PRIVATE__::assertPositiveOrZero($number);
  __PRIVATE__::assertCallable($hof);
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
  return call_user_func_array(__PRIVATE__::$instance[nAry], func_get_args());
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
  __PRIVATE__::assertCallable($hof);
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
  __PRIVATE__::assertCallable($hof);
  return __PRIVATE__::curry(2, function ($x, $y) use (&$hof) {
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
  __PRIVATE__::assertCallable($hof);
  return __PRIVATE__::curry(3, function ($x, $y, $z) use ($hof) {
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
  __PRIVATE__::assertCallable($hof);
  return function () use ($hof) {
    return $hof();
  };
}

// == nullary ==

// -- flip --
const flip = __NAMESPACE__ . '\flip';
/**
 * Takes a callable, then flips the two next arguments before calling the function
 * @param callable
 * @return \Closure f(a, b, ....z) -> f(b,a, ... z)
 */
function flip($hof)
{
  __PRIVATE__::assertCallable($hof);
  return __PRIVATE__::curry(2, function (...$args) use ($hof) {
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
__PRIVATE__::$instance[combine] = __PRIVATE__::curry(2, function ($x, $y) {
  __PRIVATE__::assertCallable($x);
  __PRIVATE__::assertCallable($y);
  return function ($z) use ($x, $y) {
    return call_user_func($x, call_user_func($y, $z));
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
  return call_user_func_array(__PRIVATE__::$instance[combine], func_get_args());
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
  return function ($ignored = null) use (&$first) {
    return $first;
  };

}

// == Kestrel ==

// -- Kite --
const KI = __NAMESPACE__ . '\KI';
/**
 * @param $ignored = This parameter will be ignored
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
__PRIVATE__::$instance[S] = __PRIVATE__::curry(3, function ($x, $y, $z) {
  __PRIVATE__::assertCallable($x);
  __PRIVATE__::assertCallable($y);
  $x_z = call_user_func($x, $z);
  __PRIVATE__::assertCallable($x_z);
  return call_user_func($x_z, call_user_func($y, $z));
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
  return call_user_func_array(__PRIVATE__::$instance[S], func_get_args());
}

// == Starling ==

// -- fold --
const fold = __NAMESPACE__ . '\fold';
__PRIVATE__::$instance[fold] = __PRIVATE__::curry(3, function ($hof, $startVal, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof NaturalTransformationInterface) {
    return $arrayLike->fold($hof, $startVal);
  }
  __PRIVATE__::assertTraversable($arrayLike);
  $output = $startVal;
  foreach ($arrayLike as $key => $value) {
    $output = call_user_func($hof, $output, $value, $key, $arrayLike);
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
  return call_user_func_array(__PRIVATE__::$instance[fold], func_get_args());
}

// == fold ==

// -- reduce --
const reduce = __NAMESPACE__ . '\reduce';
__PRIVATE__::$instance[reduce] = __PRIVATE__::curry(2, function ($hof, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof NaturalTransformationInterface) {
    return $arrayLike->reduce($hof);
  }
  __PRIVATE__::assertTraversable($arrayLike);
  $iter = is_array($arrayLike) ? new \ArrayIterator($arrayLike) : $arrayLike;
  $iter->rewind();
  if (!$iter->valid()) {
    throw new \InvalidArgumentException('Cannot reduce on collection of less than one. Behaviour is undefined');
  }
  $output = $iter->current();
  $iter->next();
  while ($iter->valid()) {
    $output = call_user_func($hof, $output, $iter->current(), $iter->key(), $arrayLike);
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
  return call_user_func_array(__PRIVATE__::$instance[reduce], func_get_args());
}

// == reduce ==

// -- map --
const map = __NAMESPACE__ . '\map';
__PRIVATE__::$instance[map] = __PRIVATE__::curry(2, function (callable $hof, $traversable) {

  // Reflect on natural transformations
  if ($traversable instanceof NaturalTransformationInterface) {
    return $traversable->map($hof);
  }
  __PRIVATE__::assertTraversable($traversable);
  $output = [];
  foreach ($traversable as $key => $value) {
    $output[$key] = call_user_func($hof, $value, $key, $traversable);
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
  return call_user_func_array(__PRIVATE__::$instance[map], func_get_args());
}

// == map ==

// -- callWith --
const callWith = __NAMESPACE__ . '\callWith';
__PRIVATE__::$instance[callWith] = __PRIVATE__::curry(2, function ($accessor, $container) {
  $callable = is_array($container) ?
    (isset($container[$accessor]) ? $container[$accessor] : null)
    : [$container, $accessor];
  __PRIVATE__::assertCallable($callable);
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
  return call_user_func_array(__PRIVATE__::$instance[callWith], func_get_args());
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

