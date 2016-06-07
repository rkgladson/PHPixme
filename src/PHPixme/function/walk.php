<?php
namespace PHPixme;
/**
 * map
 * @param callable $hof
 * @param array|FunctorInterface|\Traversable $collection
 * @return \Closure|$collection
 */
function walk($hof = null, $collection = null)
{
  return call_user_func_array(__PRIVATE__::$instance[walk], func_get_args());
}
const walk = __NAMESPACE__ . '\walk';
__PRIVATE__::$instance[walk] = __PRIVATE__::curryExactly2(function ($hof, $collection) {
  __CONTRACT__::argIsACallable($hof);
  __CONTRACT__::argIsATraversable($collection, 1);
  
  if ($collection instanceof FunctorInterface) {
    return $collection->walk($hof);
  }
  
  $array = __PRIVATE__::getArrayFrom($collection);
  if ($array !== null) {
    array_walk($array, $hof, $collection);
  } else {
    foreach (__PRIVATE__::copyTransversable($collection) as $k => $v) {
      call_user_func($hof, $v, $k, $collection);
    }
  }
  return $collection;
});
