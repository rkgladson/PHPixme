<?php
namespace PHPixme;
/**
 * cat, short for string concatenation, a stand in for .
 * @param string $lhs
 * @param string $rhs
 * @return string|\Closure
 */
function cat($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[cat], func_get_args());
}
const cat = __NAMESPACE__ . '\cat';
__PRIVATE__::$instance[cat] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs . $rhs;
});
