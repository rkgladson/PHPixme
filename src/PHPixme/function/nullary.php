<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/12/2016
 * Time: 12:53 PM
 */

namespace PHPixme;

/**
 * Wrap a function in one that will eat all arguments
 * @param $hof
 * @return \Closure
 * @sig Callable (* -> x) -> \Closure (->x)
 */
function nullary(callable $hof)
{
  __PRIVATE__::assertCallable($hof);
  return function () use ($hof) {
    return call_user_func($hof);
  };
}
const nullary = __NAMESPACE__ . '\nullary';
