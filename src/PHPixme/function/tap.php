<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/12/2016
 * Time: 1:11 PM
 */

namespace PHPixme;
/**
 * Tap a 
 *
 * @param $callable
 * @return \Closure (x->x)
 * @sig Callable -> \Closure (x->x)
 */
function tap($callable)
{
  __PRIVATE__::assertCallable($callable);
  return function ($value) use ($callable) {
    call_user_func($callable, $value);
    return $value;
  };
}
const tap = __NAMESPACE__ . '\tap';
