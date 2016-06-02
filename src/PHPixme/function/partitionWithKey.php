<?php
namespace PHPixme;
/**
 * partitionWithKey
 * Like filter, but keeps both sides of the comparison. ["true"=>[[key, value]...], "false"=>[[key, value]...]]
 * @param callable $fn
 * @param array|\Traversable $arrayLike
 * @return array|\Closure
 */
function partitionWithKey($fn = null, $arrayLike = null)
{
  return call_user_func_array(__PRIVATE__::$instance[partitionWithKey], func_get_args());
}
const partitionWithKey = __NAMESPACE__ . '\partitionWithKey';
__PRIVATE__::$instance[partitionWithKey] = __PRIVATE__::curryExactly2(function ($fn, $arrayLike) {
  __CONTRACT__::argIsACallable($fn);
  __CONTRACT__::argIsATraversable($arrayLike, 1);
  
  if ($arrayLike instanceof GroupableInterface) {
    return $arrayLike->partitionWithKey($fn);
  }
  $output = ["false" => [], "true" => []];
  foreach (__PRIVATE__::protectTraversable($arrayLike) as $key => $value) {
    $output[call_user_func($fn, $value, $key, $arrayLike) ? "true" : "false"][] = [$key, $value];
  }
  return $output;
});
