<?php
namespace PHPixme;
/**
 * pluckObjectWith
 * Creates a function to access the property of an object
 * @param string $accessor
 * @param Object $container
 * @return \Closure ($object) -> object->accessor
 * @sig String -> Object -> \Closure (->x)
 */
function pluckObjectWith($accessor = null, $container = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pluckObjectWith], func_get_args());
}
const pluckObjectWith = __NAMESPACE__ . '\pluckObjectWith';
__PRIVATE__::$instance[pluckObjectWith] = __PRIVATE__::curryExactly2(function ($accessor, $container) {
  return $container->{$accessor};
});
