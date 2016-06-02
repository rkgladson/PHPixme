<?php
namespace PHPixme;

/**
 * @param Callable $decorator
 * @param Callable $fn
 * @return \Closure
 */
function provided($decorator = null, $fn = null)
{
  return call_user_func_array(__PRIVATE__::$instance[provided], func_get_args());
}
const provided = __NAMESPACE__ . '\provided';
__PRIVATE__::$instance[provided] = __PRIVATE__::curryExactly2(function ($predicate, $fn) {
  __CONTRACT__::argIsACallable($predicate);
  __CONTRACT__::argIsACallable($fn, 1);
  
  return function () use ($predicate, $fn) {
    $args = func_get_args();
    return call_user_func_array($predicate, $args)
      ? call_user_func_array($fn, $args)
      : null;
  };
});
