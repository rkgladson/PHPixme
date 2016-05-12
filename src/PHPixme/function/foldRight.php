<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param mixed = $startVal
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig (Callable (a, b) -> a) -> a -> \Traversable [b] -> a
 */
function foldRight(callable $hof = null, $startVal = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[foldRight], func_get_args());
}
const foldRight = __NAMESPACE__ . '\foldRight';
__PRIVATE__::$instance[foldRight] = __PRIVATE__::curryExactly3(function (callable $hof, $startVal, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof CollectionInterface) {
    return $arrayLike->foldRight($hof, $startVal);
  } elseif (is_array($arrayLike)) {
    $output = $startVal;
    // Make a new copy of the array to avoid contaminating the internal pointer
    $array = $arrayLike;
    end($array);
    while (!is_null($key = key($array))) {
      $output = call_user_func($hof, $output, current($array), $key, $arrayLike);
      prev($array);
    }
    return $output;
  }
  $pairs = [];
  foreach (__PRIVATE__::copyTransversable($arrayLike) as $key => $value) {
    array_unshift($pairs, [$key, $value]);
  }
  $output = $startVal;
  foreach ($pairs as $kp) {
    $output = call_user_func($hof, $output, $kp[1], $kp[0], $arrayLike);
  }
  return $output;
});
