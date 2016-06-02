<?php
namespace PHPixme;
/**
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 */
function except($decorator = null, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[except], func_get_args());
}
const except = __NAMESPACE__ . '\except';
__PRIVATE__::$instance[except] = __PRIVATE__::curryExactly2(function ($predicate, $fn) {
  __CONTRACT__::argIsACallable($predicate);
  __CONTRACT__::argIsACallable($fn, 1);
  return function () use ($predicate, $fn) {
    $args = func_get_args();
    return call_user_func_array($predicate, $args)
      ? null
      : call_user_func_array($fn, $args);
  };
});
