<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param mixed $startVal
 * @param \Traversable $traversable
 * @return \Closure|mixed
 * @sig (Callable (a, b) -> a) -> a -> \Traversable [b] -> a
 */
function fold($hof = null, $startVal = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[fold], func_get_args());
}
const fold = __NAMESPACE__ . '\fold';
__PRIVATE__::$instance[fold] = __PRIVATE__::curryExactly3(function ($hof, $startVal, $arrayLike) {
  __CONTRACT__::argIsACallable($hof);
  if ($arrayLike instanceof FoldableInterface) {
    return $arrayLike->fold($hof, $startVal);
  }
  
  $output = $startVal;
  foreach (__PRIVATE__::protectTraversable($arrayLike) as $key => $value) {
    $output = call_user_func($hof, $output, $value, $key, $arrayLike);
  }
  return $output;
});
