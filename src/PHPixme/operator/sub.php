<?php
namespace PHPixme;
/**
 * sub, short for subtract, a stand in for -
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function sub($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[sub], func_get_args());
}
const sub = __NAMESPACE__ . '\sub';
__PRIVATE__::$instance[sub] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs - $rhs;
});
