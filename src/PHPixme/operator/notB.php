<?php
namespace PHPixme;
/**
 * notB, not binary, invert, or 2's compliment, a stand in for ~
 * @sig (int $rhs) -> int $z
 * @param int $rhs
 * @return int|\Closure
 */
function notB($rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[notB], func_get_args());
}
const notB = __NAMESPACE__ . '\notB';
__PRIVATE__::$instance[notB] = __PRIVATE__::curryExactly1(function ($rhs) {
  return ~$rhs;
});
