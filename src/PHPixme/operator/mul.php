<?php
namespace PHPixme;
/**
 * mul, short for multiply, a stand in for *
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function mul($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[mul], func_get_args());
}
const mul = __NAMESPACE__ . '\mul';
__PRIVATE__::$instance[mul] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs * $rhs;
});
