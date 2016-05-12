<?php
namespace PHPixme;
/**
 * xorL, short for logical xor, a stand in for xor keyword
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function xorL($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[xorL], func_get_args());
}
const xorL = __NAMESPACE__ . '\xorL';
__PRIVATE__::$instance[xorL] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs xor $rhs;
});
