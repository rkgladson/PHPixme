<?php
namespace PHPixme;
/**
 * neq, short for not equals, a stand in for !=
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function neq($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[neq], func_get_args());
}
const neq = __NAMESPACE__ . '\neq';
__PRIVATE__::$instance[neq] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs != $rhs;
});
