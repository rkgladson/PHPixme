<?php
namespace PHPixme;
/**
 * pow, short for power. A stand in for **
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function pow($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pow], func_get_args());
}
const pow = __NAMESPACE__ . '\pow';
__PRIVATE__::$instance[pow] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs ** $rhs;
});
