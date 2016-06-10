<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig Callable (a, b -> a) -> \Traversable[a,b] -> a
 */
function reduceRight($hof = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[reduceRight], func_get_args());
}
const reduceRight = __NAMESPACE__ . '\reduceRight';
__PRIVATE__::$instance[reduceRight] = __PRIVATE__::curryExactly2(function ($hof, $arrayLike) {
  __CONTRACT__::argIsACallable($hof);
  __CONTRACT__::argIsATraversable($arrayLike, 1);

  if ($arrayLike instanceof ReducibleInterface) {
    return $arrayLike->reduceRight($hof);
  }

  // Equalize the usefulness
  $array = __CONTRACT__::isNonEmpty(__PRIVATE__::traversableToArray($arrayLike));

  $output = end($array);
  $value = prev($array);
  // Traverse using the internal pointer to avoid creating additional work
  while (($key = key($array)) !== null) {
    $output = call_user_func($hof, $output, $value, $key, $arrayLike);
    $value = prev($array);
  }
  return $output;

});
