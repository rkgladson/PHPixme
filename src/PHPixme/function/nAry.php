<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/12/2016
 * Time: 12:03 PM
 */

namespace PHPixme;

/**
 * Wrap a function in an argument that will eat all but n arguments
 * @param int $arity
 * @param callable = $hof
 * @return \Closure
 * @sig Integer -> Callable (* -> x) -> \Closure (* -> x)
 */
function nAry($arity = null, callable $hof = null)
{
  return call_user_func_array(__PRIVATE__::$instance[nAry], func_get_args());
}
const nAry = __NAMESPACE__ . '\nAry';
__PRIVATE__::$instance[nAry] = __PRIVATE__::curryExactly2(function ($number = 0, $hof = null) {
  __PRIVATE__::assertPositiveOrZero($number);
  __PRIVATE__::assertCallable($hof);
  return function () use ($number, $hof) {
    $args = func_get_args();
    return call_user_func_array($hof, array_slice($args, 0, $number));
  };
});
