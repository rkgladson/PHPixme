<?php
namespace PHPixme;
/**
 * lte, short for greater than or equal to, a stand in for >=
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function lte($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lte], func_get_args());
}
const lte = __NAMESPACE__ . '\lte';
__PRIVATE__::$instance[lte] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs <= $rhs;
});
