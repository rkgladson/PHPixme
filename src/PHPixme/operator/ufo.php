<?php
namespace PHPixme;
/**
 * ufo, short for the space ship operator, or the three way comparison, a stand in for <=>
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return int|\Closure
 */
function ufo($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[ufo], func_get_args());
}
const ufo = __NAMESPACE__ . '\ufo';
__PRIVATE__::$instance[ufo] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs != $rhs ? $lhs > $rhs ? 1 : -1 : 0;
});
