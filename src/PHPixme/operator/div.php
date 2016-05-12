<?php
namespace PHPixme;
/**
 * div, short for divide, a stand in for /
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function div($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[div], func_get_args());
}
const div = __NAMESPACE__ . '\div';
__PRIVATE__::$instance[div] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs / $rhs;
});
