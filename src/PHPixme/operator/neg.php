<?php
namespace PHPixme;
/**
 * neg short for negation, a stand in for -#
 * @sig (int|float $rhs)-> -$lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function neg($rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[neg], func_get_args());
}
const neg = __NAMESPACE__ . '\neg';
__PRIVATE__::$instance[neg] = __PRIVATE__::curryExactly1(function ($rhs) {
  return -$rhs;
});
