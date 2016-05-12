<?php
namespace PHPixme;
/**
 * pluckArrayWith
 * Creates a function to access the property of an object
 * @param string $accessor
 * @param array $container
 * @return \Closure ($object) -> object->accessor
 * @sig String -> Array -> \Closure (->x)
 */
function pluckArrayWith($accessor = null, $container = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pluckArrayWith], func_get_args());
}
const pluckArrayWith = __NAMESPACE__ . '\pluckArrayWith';
__PRIVATE__::$instance[pluckArrayWith] = __PRIVATE__::curryExactly2(function ($accessor, $container) {
  return $container[$accessor];
});
