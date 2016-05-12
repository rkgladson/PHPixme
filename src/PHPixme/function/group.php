<?php
namespace PHPixme;
/**
 * Group a traversable by a return value, separating them out into [groupName=>[value...]]
 * @param callable $fn
 * @param array|\Traversable $arrayLike
 * @return array|\Closure
 */
function group(callable $fn = null, $arrayLike = null)
{
  return call_user_func_array(__PRIVATE__::$instance[group], func_get_args());
}
const group = __NAMESPACE__ . '\group';
__PRIVATE__::$instance[group] = __PRIVATE__::curryExactly2(function (callable $fn, $arrayLike) {
  if ($arrayLike instanceof GroupableInterface) {
    return $arrayLike->group($fn);
  }
  $output = [];
  foreach (__PRIVATE__::copyTransversable($arrayLike) as $key => $value) {
    $output[call_user_func($fn, $value, $key, $arrayLike)][] = $value;
  }
  return $output;
});