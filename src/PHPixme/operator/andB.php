<?php
namespace PHPixme;
/**
 * andB, short for bitwise and, a stand in for &
 * @sig (int $lhs)->(int $rhs)-> int $z
 * @param int $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function andB($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[andB], func_get_args());
}
const andB = __NAMESPACE__ . '\andB';
__PRIVATE__::$instance[andB] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs & $rhs;
});
