<?php
namespace PHPixme;
/**
 * Group a traversable by a return value, separating them out into a nested array of [groupName=>[[key, value]...]]
 * @param callable $fn
 * @param array|\Traversable $arrayLike
 * @return array|\Closure
 */
function groupWithKey(callable $fn = null, $arrayLike = null)
{
  return call_user_func_array(__PRIVATE__::$instance[groupWithKey], func_get_args());
}
const groupWithKey = __NAMESPACE__ . '\groupWithKey';
__PRIVATE__::$instance[groupWithKey] = __PRIVATE__::curryExactly2(function (callable $fn, $arrayLike) {
  if ($arrayLike instanceof GroupableInterface) {
    return $arrayLike->groupWithKey($fn);
  }
  $output = [];
  foreach (__PRIVATE__::copyTransversable($arrayLike) as $key => $value) {
    $output[call_user_func($fn, $value, $key, $arrayLike)][] = [$key, $value];
  }
  return $output;
});