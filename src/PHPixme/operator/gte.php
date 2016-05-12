<?php
namespace PHPixme;
/**
 * gte, short for greater than or equal to, a stand in for >=
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function gte($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[gte], func_get_args());
}
const gte = __NAMESPACE__ . '\gte';
__PRIVATE__::$instance[gte] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs > $rhs;
});