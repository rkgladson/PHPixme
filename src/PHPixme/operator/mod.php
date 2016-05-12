<?php
namespace PHPixme;
/**
 * mod, short for modulous, a stand in for %
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function mod($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[mod], func_get_args());
}
const mod = __NAMESPACE__ . '\mod';
__PRIVATE__::$instance[mod] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs % $rhs;
});
