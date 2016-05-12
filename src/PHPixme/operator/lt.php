<?php
namespace PHPixme;
/**
 * lt, short for greater than, a stand in for >
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function lt($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lt], func_get_args());
}
const lt = __NAMESPACE__ . '\lt';
__PRIVATE__::$instance[lt] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs < $rhs;
});
