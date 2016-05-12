<?php
namespace PHPixme;
/**
 * shiftR, or shift right, a stand in for >>
 * @param int|float $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function shiftR($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[shiftR], func_get_args());
}
const shiftR = __NAMESPACE__ . '\shiftR';
__PRIVATE__::$instance[shiftR] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs >> $rhs;
});
