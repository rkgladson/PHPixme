<?php
namespace PHPixme;
/**
 * id, short for identity, or strictly equals, a stand in for ===
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function id($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[id], func_get_args());
}
const id = __NAMESPACE__ . '\id';
__PRIVATE__::$instance[id] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs === $rhs;
});
