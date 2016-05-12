<?php
namespace PHPixme;

/**
 * Take a callable and produce a curried \Closure
 * @param int $arity
 * @param callable = $hof
 * @return \Closure
 * @sig Integer -> Callable (*-> x) -> \Closure (* -> x)
 */
function curry($arity = null, callable $hof = null)
{
  return call_user_func_array(__PRIVATE__::$instance[curry], func_get_args());
}
const curry = __NAMESPACE__ . '\curry';
__PRIVATE__::$instance[curry] = __PRIVATE__::curryExactly2(function ($arity, callable $callable) {
  __PRIVATE__::assertPositiveOrZero($arity);
  __PRIVATE__::assertCallable($callable);
  // The function is static so that is easier to recurse,
  // as it shares no state with itself outside its arguments.
  return __PRIVATE__::curryGiven([], $arity, $callable);
});
