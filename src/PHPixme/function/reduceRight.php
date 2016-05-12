<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig Callable (a, b -> a) -> \Traversable[a,b] -> a
 */
function reduceRight(callable $hof = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[reduceRight], func_get_args());
}
const reduceRight = __NAMESPACE__ . '\reduceRight';
__PRIVATE__::$instance[reduceRight] = __PRIVATE__::curryExactly2(function (callable $hof, $arrayLike) {
  if ($arrayLike instanceof ReducibleInterface) {
    return $arrayLike->reduceRight($hof);
  }
  if (is_array($arrayLike)) {
    if (empty($arrayLike)) {
      throw new \LengthException('Cannot reduceRight on empty collections. Behaviour is undefined');
    }
    // Make a new copy of the array to avoid contaminating the internal pointer
    $array = $arrayLike;
    $output = end($array);
    $value = prev($array);
    // Traverse using the internal pointer to avoid creating additional work
    while (!is_null($key = key($array))) {
      $output = call_user_func($hof, $output, $value, $key, $arrayLike);
      $value = prev($array);
    }
    return $output;
  }
  // Traversables can only go forward.
  $pairs = [];
  foreach (__PRIVATE__::copyTransversable($arrayLike) as $key => $value) {
    array_unshift($pairs, [$key, $value]);
  }
  // This can only be known after iterating the Traversable
  if (empty($pairs)) {
    throw new \LengthException('Cannot reduceRight on empty collections. Behaviour is undefined');
  }
  $output = array_shift($pairs)[1]; // Get the first value
  foreach ($pairs as $kp) {
    $output = call_user_func($hof, $output, $kp[1], $kp[0], $arrayLike);
  }
  return $output;

});
