<?php
namespace PHPixme;
/**
 * gt, short for greater than, a stand in for >
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function gt($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[gt], func_get_args());
}
const gt = __NAMESPACE__ . '\gt';
__PRIVATE__::$instance[gt] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs > $rhs;
});
