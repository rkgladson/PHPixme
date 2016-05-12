<?php
namespace PHPixme;
/**
 * app, short for application or array concatenation, a stand in for + on arrays
 * @sig (array $lhs) -> (array $rhs) -> array $z
 * @param array $lhs
 * @param array $rhs
 * @return array|\Closure
 */
function app($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[add], func_get_args());
}
const app = __NAMESPACE__ . '\app';
