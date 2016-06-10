<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig Callable (a, b -> a) -> \Traversable[a,b] -> a
 */
function reduce($hof = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[reduce], func_get_args());
}
const reduce = __NAMESPACE__ . '\reduce';
__PRIVATE__::$instance[reduce] = __PRIVATE__::curryExactly2(function ($hof, $arrayLike) {
  __CONTRACT__::argIsACallable($hof);
  __CONTRACT__::argIsATraversable($arrayLike, 1);
  
  if ($arrayLike instanceof ReducibleInterface) {
    return $arrayLike->reduce($hof);
  }
  
  // Equalize the usefulness
  $array = __CONTRACT__::isNonEmpty(__PRIVATE__::traversableToArray($arrayLike));
  
  $output = current($array);
  next($array);
  while (null !== ($key = key($array))) {
    $output = call_user_func($hof, $output, current($array), $key, $arrayLike);
    next($array);
  }
  return $output;
});