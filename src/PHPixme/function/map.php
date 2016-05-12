<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param array|\Traversable|CollectionInterface $traversable
 * @return \Closure|mixed
 * @sig Callable (a -> b) -> \Traversable[a] -> \Traversable[b]
 *
 */
function map(callable $hof = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[map], func_get_args());
}
const map = __NAMESPACE__ . '\map';
__PRIVATE__::$instance[map] = __PRIVATE__::curryExactly2(function (callable $hof, $traversable) {
  // Reflect on natural transformations
  if ($traversable instanceof CollectionInterface) {
    return $traversable->map($hof);
  }
  $output = [];
  foreach (__PRIVATE__::copyTransversable($traversable) as $key => $value) {
    $output[$key] = call_user_func($hof, $value, $key, $traversable);
  }
  return $output;
});