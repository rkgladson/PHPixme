<?php
namespace PHPixme;
/**
 * add, short for addition, a stand in for +
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function add($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[add], func_get_args());
}
const add = __NAMESPACE__ . '\add';
__PRIVATE__::$instance[add] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs + $rhs;
});
