<?php
namespace PHPixme;
/**
 * eq, short for equals, a stand in for ==
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function eq($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[eq], func_get_args());
}
const eq = __NAMESPACE__ . '\eq';
__PRIVATE__::$instance[eq] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs == $rhs;
});
