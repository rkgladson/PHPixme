<?php
namespace PHPixme;

// -- Starling --
/**
 * Starling
 * This combinator takes two functions and applies the third across both.
 * The first operand must return a function, which will relieve the result of the second.
 * Useful for combinding mappings of the same data together somehow.
 * @param callable $x
 * @param callable = $y
 * @param mixed = $z
 * @return \Closure|mixed
 * @sig Callable x -> Callable y -> z -> a
 */
function S(callable $x = null, $y = null, $z = null)
{
  return call_user_func_array(__PRIVATE__::$instance[S], func_get_args());
}
const S = __NAMESPACE__ . '\S';
__PRIVATE__::$instance[S] = __PRIVATE__::curryExactly3(function ($x, $y, $z) {
  __PRIVATE__::assertCallable($x);
  __PRIVATE__::assertCallable($y);
  $x_z = call_user_func($x, $z);
  __PRIVATE__::assertCallable($x_z);
  return call_user_func($x_z, call_user_func($y, $z));
});

