<?php
namespace PHPixme;
/**
 * xorL, short for logical xor, a stand in for xor keyword
 * @sig (int $lhs)->(int $rhs)-> int $z
 * @param int $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function xorB($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[xorB], func_get_args());
}
const xorB = __NAMESPACE__ . '\xorB';
__PRIVATE__::$instance[xorB] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs ^ $rhs;
});
