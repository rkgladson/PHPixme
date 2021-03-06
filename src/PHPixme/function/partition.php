<?php
namespace PHPixme;
/**
 * Like filter, but keeps both sides of the comparison. ["true"=>[value...], "false"=>[value...]]
 * @param callable $fn
 * @param boolean
 * @param array|\Traversable $arrayLike
 * @return array|\Closure
 */
function partition($fn = null, $preserveKeys = null, $arrayLike = null)
{
  return call_user_func_array(__PRIVATE__::$instance[partition], func_get_args());
}
const partition = __NAMESPACE__ . '\partition';
__PRIVATE__::$instance[partition] = __PRIVATE__::curryExactly2(function ($fn, $arrayLike) {
  __CONTRACT__::argIsACallable($fn);
  __CONTRACT__::argIsATraversable($arrayLike, 1);
  
  if ($arrayLike instanceof GroupableInterface) {
    return $arrayLike->partition($fn);
  }
  
  $output = ["false" => [], "true" => []];
  foreach (__PRIVATE__::protectTraversable($arrayLike) as $key => $value) {
    $output[call_user_func($fn, $value, $key, $arrayLike) ? "true" : "false"][] = $value;
  }
  return $output;
});