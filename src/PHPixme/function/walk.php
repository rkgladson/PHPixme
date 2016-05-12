<?php
namespace PHPixme;
/**
 * map
 * @param callable $hof
 * @param array|CollectionInterface|\Traversable $collection
 * @return \Closure|$collection
 */
function walk(callable $hof = null, $collection = null)
{
  return call_user_func_array(__PRIVATE__::$instance[walk], func_get_args());
}
const walk = __NAMESPACE__ . '\walk';
__PRIVATE__::$instance[walk] = __PRIVATE__::curryExactly2(function (callable $hof, $collection) {
  if (is_array($collection)) {
    array_walk($collection, $hof, $collection);
  } else if ($collection instanceof CollectionInterface) {
    return $collection->walk($hof);
  } else {
    foreach (__PRIVATE__::copyTransversable($collection) as $k => $v) {
      call_user_func($hof, $v, $k, $collection);
    }
  }
  return $collection;
});
