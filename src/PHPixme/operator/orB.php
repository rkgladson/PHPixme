<?php
namespace PHPixme;
/**
 * orB, short for logical or, a stand in for |
 * @sig (int $lhs)->(int $rhs)-> int $z
 * @param int $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function orB($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[orB], func_get_args());
}
const orB = __NAMESPACE__ . '\orB';
__PRIVATE__::$instance[orB] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs | $rhs;
});