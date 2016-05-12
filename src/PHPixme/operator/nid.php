<?php
namespace PHPixme;
/**
 * nid, short for not the identity, or not strictly equals, a stand in for !==
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function nid($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[nid], func_get_args());
}
const nid = __NAMESPACE__ . '\nid';
__PRIVATE__::$instance[nid] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs !== $rhs;
});
