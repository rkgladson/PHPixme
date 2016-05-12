<?php
namespace PHPixme;
/**
 * notL, or invert, stand in for !
 * @sig ($rhs) -> boolean $z
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function notL($rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[notL], func_get_args());
}
const notL = __NAMESPACE__ . '\notL';
__PRIVATE__::$instance[notL] = __PRIVATE__::curryExactly1(function ($rhs) {
  return !$rhs;
});
