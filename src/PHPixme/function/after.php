<?php
namespace PHPixme;
/**
 * After Decorator
 * Decorate a callable using the return value of the decorated callable as the argument
 * for this decorative function. It does not modify the return value.
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 * @sig Callable (x->) -> Callable (*->x) -> Closure (*->x)
 */
function after($decorator = null, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[after], func_get_args());
}
const after = __NAMESPACE__ . '\after';
__PRIVATE__::$instance[after] = __PRIVATE__::curryExactly2(function ($decorator, $fn) {
  __PRIVATE__::assertCallable($decorator);
  __PRIVATE__::assertCallable($fn);
  return function () use ($decorator, $fn) {
    $value = call_user_func_array($fn, func_get_args());
    call_user_func($decorator, $value);
    return $value;
  };
});
