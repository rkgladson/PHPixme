<?php
namespace PHPixme;
/**
 * Like filter, but keeps both sides of the comparison. ["true"=>[value...], "false"=>[value...]]
 * @param callable $fn
 * @param boolean
 * @param array|\Traversable $arrayLike
 * @return array|\Closure
 */
function partition(callable $fn = null, $preserveKeys = null, $arrayLike = null)
{
  return call_user_func_array(__PRIVATE__::$instance[partition], func_get_args());
}
const partition = __NAMESPACE__ . '\partition';
__PRIVATE__::$instance[partition] = __PRIVATE__::curryExactly2(function (callable $fn, $arrayLike) {
  if ($arrayLike instanceof GroupableInterface) {
    return $arrayLike->partition($fn);
  }
  $output = ["false" => [], "true" => []];
  foreach (__PRIVATE__::copyTransversable($arrayLike) as $key => $value) {
    $output[call_user_func($fn, $value, $key, $arrayLike) ? "true" : "false"][] = $value;
  }
  return $output;
});