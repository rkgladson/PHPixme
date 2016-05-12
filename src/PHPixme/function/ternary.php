<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/12/2016
 * Time: 12:52 PM
 */

namespace PHPixme;

/**
 * Wrap a callable function in one that will eat all but three arguments
 * @param callable $hof
 * @return \Closure
 * @sig Callable (* -> x) -> \Closure (a, b, c -> x)
 */
function ternary(callable $hof)
{
  return function () use ($hof) {
    return call_user_func_array($hof, array_slice(func_get_args(), 0, 3));
  };
}
const ternary = __NAMESPACE__ . '\ternary';