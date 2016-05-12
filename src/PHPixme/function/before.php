<?php
namespace PHPixme;
/**
 * Before decorator
 * Wraps a Closure around a callable, running the decorating callable with the same arguments
 * before executing and returning the value of the decorated function
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 * @sig Callable (*->) -> Callable (*->x) -> Closure (*->x)
 */
function before($decorator = null, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[before], func_get_args());
}
const before = __NAMESPACE__ . '\before';
__PRIVATE__::$instance[before] = __PRIVATE__::curryExactly2(function ($decorator, $fn) {
  __PRIVATE__::assertCallable($decorator);
  __PRIVATE__::assertCallable($fn);
  return function () use ($decorator, $fn) {
    $args = func_get_args();
    call_user_func_array($decorator, $args);
    return call_user_func_array($fn, $args);
  };
});