<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/12/2016
 * Time: 12:51 PM
 */

namespace PHPixme;

/**
 * wrap a callable in a function that will eat but one argument
 * @param callable $hof
 * @return \Closure
 * @sig Callable (* -> x) -> \Closure (a -> x)
 */
function unary(callable $hof = null)
{
  return function ($_0) use ($hof) {
    return call_user_func($hof, $_0);
  };
}
const unary = __NAMESPACE__ . '\unary';
