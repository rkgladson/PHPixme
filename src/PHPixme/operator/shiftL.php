<?php
namespace PHPixme;
/**
 * shiftL, or shift left, a stand in for <<
 * @param int|float $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function shiftL($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[shiftL], func_get_args());
}
const shiftL = __NAMESPACE__ . '\shiftL';
__PRIVATE__::$instance[shiftL] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs << $rhs;
});
