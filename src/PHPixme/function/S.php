<?php
namespace PHPixme;

// -- Starling --
/**
 * Starling
 * This combinator takes two functions and applies the third across both.
 * The first operand must return a function, which will relieve the result of the second.
 * Useful for combining mappings of the same data together
 * @param callable $x
 * @param callable $y
 * @param mixed $z
 * @return \Closure|mixed
 * @sig Callable x -> Callable y -> z -> a
 */
function S($x = null, $y = null, $z = null)
{
  return call_user_func_array(__PRIVATE__::$instance[S], func_get_args());
}
const S = __NAMESPACE__ . '\S';
__PRIVATE__::$instance[S] = __PRIVATE__::curryExactly3(function ($x, $y, $z) {
  __CONTRACT__::argIsACallable($x);
  __CONTRACT__::argIsACallable($y, 1);
  
  return call_user_func(
    __CONTRACT__::composedIsACallable(call_user_func($x, $z))
    , call_user_func($y, $z)
  );
});

