<?php
namespace PHPixme;

// -- placeholder --
/**
 * Returns the placeholder instance that is used for placing gaps in curry
 * @return \stdClass
 */
function _()
{
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
 * @sig Integer -> Calllable (*-> x) -> \Closure (* -> x)
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
  return function () use ($number, $hof) {
    $args = func_get_args();
    return call_user_func_array($hof, array_slice($args, 0, $number));
  };
});
/**
 * Wrap a function in an argument that will eat all but n arguments
 * @param int $arity
 * @param callable = $hof
 * @return \Closure
 * @sig Integer -> Callable (* -> x) -> \Closure (* -> x)
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
 * @sig Callable (* -> x) -> \Closure (a -> x)
 */
function unary($hof)
{
  __PRIVATE__::assertCallable($hof);
  return function ($arg) use ($hof) {
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
 * @sig Callable (* -> x) -> \Closure (a, b -> x)
 */
function binary($hof)
{
  __PRIVATE__::assertCallable($hof);
  return __PRIVATE__::curry(2, function ($x, $y) use ($hof) {
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
 * @sig Callable (* -> x) -> \Closure (a, b, c -> x)
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
 * @sig Callable (* -> x) -> \Closure (->x)
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
__PRIVATE__::$instance[combine] = __PRIVATE__::curry(2, function ($x) {
  $combine = func_get_args();
  foreach ($combine as $hof) {
    __PRIVATE__::assertCallable($hof);
  }
  $combineHead = end($combine);
  $combineTail = array_slice($combine, 0, -1);
  $combineTailSize = count($combineTail);
  return function () use ($combineHead, $combineTail, $combineTailSize) {
    $acc = call_user_func_array($combineHead, func_get_args());
    for ($index = $combineTailSize - 1; -1 < $index; $index -= 1) {
      $acc = call_user_func($combineTail[$index], $acc);
    }
    return $acc;
  };
});
/**
 * Takes two functions and has the first consume the output of the second, combining them to a single function
 * @param callable $hofSecond
 * @param callable = $hofFirst
 * @return \Closure
 * @sig (Unary Callable(y -> z), ..., Unary Callable(a -> b), Callable (*->a)) -> \Closure (* -> a)
 */
function combine($hofSecond, $hofFirst = null)
{
  return call_user_func_array(__PRIVATE__::$instance[combine], func_get_args());
}

// == combine ==

// -- pipe --
const pipe = __NAMESPACE__ . '\pipe';
__PRIVATE__::$instance[pipe] = __PRIVATE__::curry(2, function ($x) {
  $pipe = func_get_args();
  foreach ($pipe as $value) {
    __PRIVATE__::assertCallable($value);
  }
  $pipeTail = array_splice($pipe, 1);
  return function () use ($x, $pipeTail) {
    $acc = call_user_func_array($x, func_get_args());
    foreach ($pipeTail as $hof) {
      $acc = call_user_func($hof, $acc);
    }
    return $acc;
  };
});
/**
 * @param $hofFirst
 * @param null $hofSecond
 * @return mixed
 * @sig (Callable (* -> a) -> Unary Callable ( a -> b ), ..., Unary Callable (y -> z)) -> \Closure (*->z)
 */
function pipe($hofFirst, $hofSecond = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pipe], func_get_args());
}

// == pipe ==

// -- Kestrel --
const K = __NAMESPACE__ . '\K';
/**
 * @param mixed $first
 * @return \Closure
 * @sig first -> ignored -> first
 */
function K($first)
{
  return function ($ignored = null) use ($first) {
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
  return function ($second) {
    return $second;
  };
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
 * @sig Callable x -> Callable y -> z -> a
 */
function S(callable $x, $y = null, $z = null)
{
  return call_user_func_array(__PRIVATE__::$instance[S], func_get_args());
}

// == Starling ==

// -- Y Combinator --
const Y = __NAMESPACE__ . '\Y';
function Y(callable $callbackContainer)
{
  $g = function (\Closure $x) use ($callbackContainer) {
    return $callbackContainer(function () use ($x) {
      return call_user_func_array($x($x), func_get_args());
    });
  };

  return $g($g);
}

// == Y Combinator ==

// -- tap --
const tap = __NAMESPACE__ . '\tap';
/**
 * @param $callable
 * @return \Closure (x->x)
 * @sig Callable -> \Closure (x->x)
 */
function tap($callable)
{
  __PRIVATE__::assertCallable($callable);
  return function ($value) use ($callable) {
    call_user_func($callable, $value);
    return $value;
  };
}

// == tap ==

// -- before --
const before = __NAMESPACE__ . '\before';
__PRIVATE__::$instance[before] = __PRIVATE__::curry(2, function ($decorator, $fn) {
  __PRIVATE__::assertCallable($decorator);
  __PRIVATE__::assertCallable($fn);
  return function () use ($decorator, $fn) {
    $args = func_get_args();
    call_user_func_array($decorator, $args);
    return call_user_func_array($fn, $args);
  };
});
/**
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 * @sig Callable (*->) -> Callable (*->x) -> Closure (*->x)
 */
function before($decorator, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[before], func_get_args());
}

// == before ==

// -- after --
const after = __NAMESPACE__ . '\after';
__PRIVATE__::$instance[after] = __PRIVATE__::curry(2, function ($decorator, $fn) {
  __PRIVATE__::assertCallable($decorator);
  __PRIVATE__::assertCallable($fn);
  return function () use ($decorator, $fn) {
    $value = call_user_func_array($fn, func_get_args());
    call_user_func($decorator, $value);
    return $value;
  };
});
/**
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 * @sig Callable (x->) -> Callable (*->x) -> Closure (*->x)
 */
function after($decorator, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[after], func_get_args());
}

// == after ==

// -- provided --
const provided = __NAMESPACE__ . '\provided';
__PRIVATE__::$instance[provided] = __PRIVATE__::curry(2, function ($predicate, $fn) {
  __PRIVATE__::assertCallable($predicate);
  __PRIVATE__::assertCallable($fn);
  return function () use ($predicate, $fn) {
    $args = func_get_args();
    return call_user_func_array($predicate, $args)
      ? call_user_func_array($fn, $args)
      : null;
  };
});
/**
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 */
function provided($decorator, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[provided], func_get_args());
}

// == provided ==

// -- except --
const except = __NAMESPACE__ . '\except';
__PRIVATE__::$instance[except] = __PRIVATE__::curry(2, function ($predicate, $fn) {
  __PRIVATE__::assertCallable($predicate);
  __PRIVATE__::assertCallable($fn);
  return function () use ($predicate, $fn) {
    $args = func_get_args();
    return call_user_func_array($predicate, $args)
      ? null
      : call_user_func_array($fn, $args);
  };
});
/**
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 */
function except($decorator, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[except], func_get_args());
}

// == except ==

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
 * @sig (Callable (a, b) -> a) -> a -> \Traversable [b] -> a
 */
function fold($hof, $startVal = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[fold], func_get_args());
}

// == fold ==
// -- foldRight --
const foldRight = __NAMESPACE__ . '\foldRight';
__PRIVATE__::$instance[foldRight] = __PRIVATE__::curry(3, function ($hof, $startVal, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof NaturalTransformationInterface) {
    return $arrayLike->foldRight($hof, $startVal);
  } elseif (is_array($arrayLike)) {
    $output = $startVal;
    // Make a new copy of the array to avoid contaminating the internal pointer
    $array = $arrayLike;
    end($array);
    while (!is_null($key = key($array))) {
      $output = call_user_func($hof, $output, current($array), $key, $arrayLike);
      prev($array);
    }
    return $output;
  }
  __PRIVATE__::assertTraversable($arrayLike);
  $pairs = [];
  foreach ($arrayLike as $key => $value) {
    array_unshift($pairs, [$key, $value]);
  }
  $output = $startVal;
  foreach ($pairs as $kp) {
    $output = call_user_func($hof, $output, $kp[1], $kp[0], $arrayLike);
  }
  return $output;
});

/**
 * @param callable $hof
 * @param mixed = $startVal
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig (Callable (a, b) -> a) -> a -> \Traversable [b] -> a
 */
function foldRight($hof, $startVal = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[foldRight], func_get_args());
}

// == foldRight ==

// -- reduce --
const reduce = __NAMESPACE__ . '\reduce';
__PRIVATE__::$instance[reduce] = __PRIVATE__::curry(2, function ($hof, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof NaturalTransformationInterface) {
    return $arrayLike->reduce($hof);
  }
  __PRIVATE__::assertTraversable($arrayLike);
  if (is_array($arrayLike)) {

  }
  $iter = is_array($arrayLike) ? new \ArrayIterator($arrayLike) : $arrayLike;
  if (!$iter->valid()) {
    throw new \LengthException('Cannot reduce on emty collections. Behaviour is undefined');
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
 * @sig Callable (a, b -> a) -> \Traversable[a,b] -> a
 */
function reduce($hof, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[reduce], func_get_args());
}

// == reduce ==

// -- reduceRight --
const reduceRight = __NAMESPACE__ . '\reduceRight';
__PRIVATE__::$instance[reduceRight] = __PRIVATE__::curry(2, function ($hof, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof NaturalTransformationInterface) {
    return $arrayLike->reduceRight($hof);
  }
  __PRIVATE__::assertTraversable($arrayLike);
  if (is_array($arrayLike)) {
    if (empty($arrayLike)) {
      throw new \LengthException('Cannot reduceRight on empty collections. Behaviour is undefined');
    }
    // Make a new copy of the array to avoid contaminating the internal pointer
    $array = $arrayLike;
    $output = end($array);
    $value = prev($array);
    // Traverse using the internal pointer to avoid creating additional work
    while (!is_null($key = key($array))) {
      $output = call_user_func($hof, $output, $value, $key, $arrayLike);
      $value = prev($array);
    }
    return $output;
  }
  // Traversables can only go forward.
  $pairs = [];
  foreach ($arrayLike as $key => $value) {
    array_unshift($pairs, [$key, $value]);
  }
  // This can only be known after iterating the Traversable
  if (empty($pairs)) {
    throw new \LengthException('Cannot reduceRight on empty collections. Behaviour is undefined');
  }
  $output = array_shift($pairs)[1]; // Get the first value
  foreach ($pairs as $kp) {
    $output = call_user_func($hof, $output, $kp[1], $kp[0], $arrayLike);
  }
  return $output;

});
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig Callable (a, b -> a) -> \Traversable[a,b] -> a
 */
function reduceRight($hof, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[reduceRight], func_get_args());
}

// == reduceRight ==

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
 * @sig Callable (a -> b) -> \Traversable[a] -> \Traversable[b]
 *
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
  return function () use ($callable) {
    return call_user_func_array($callable, func_get_args());
  };
});
/**
 * Produce a function that calls a function within a array or object
 * @param string $accessor
 * @param object|array $container
 * @return \Closure ($container) -> ((args) -> $container{[$accessor]}(...args))
 * @sig String -> Object -> \Closure (*->x)
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
 * @sig String -> Object -> \Closure (->x)
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
 * @sig String -> Array -> \Closure (->x)
 */
function pluckArrayWith($accessor)
{
  return function ($container) use ($accessor) {
    return $container[$accessor];
  };
}

// == pluckArrayWith ==

// -- trampoline --
const trampoline = __NAMESPACE__ . '\trampoline';
function trampoline(callable $fn)
{
  return function () use ($fn) {
    $result = call_user_func_array($fn, func_get_args());
    while ($result instanceof \Closure) {
      $result = $result();
    }
    return $result;
  };
}

// == trampoline ==

// -- noop --
const noop = __NAMESPACE__ . '\noop';
/**
 * It does nothing!
 * return null
 */
function noop()
{
  // this space is intentionally left blank
}

// == noop ==