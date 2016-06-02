<?php
namespace PHPixme;
/**
 * callWith
 * Produce a function that calls a function within a array or object
 * @param string $accessor
 * @param object|array $container
 * @return \Closure ($container) -> ((args) -> $container{[$accessor]}(...args))
 * @sig String -> Object|Array -> \Closure (*->x)
 */
function callWith($accessor = null, $container = null)
{
  return call_user_func_array(__PRIVATE__::$instance[callWith], func_get_args());
}
const callWith = __NAMESPACE__ . '\callWith';
__PRIVATE__::$instance[callWith] = __PRIVATE__::curryExactly2(function ($accessor, $container) {
  
  $callable = __CONTRACT__::composedIsACallable(
    is_array($container) ? (array_key_exists($accessor, $container) ? $container[$accessor] : null) : [$container, $accessor]
  );
  return function () use ($callable) {
    return call_user_func_array($callable, func_get_args());
  };
});
