<?php
namespace PHPixme;
/**
 * Tap a 
 *
 * @param callable $callable
 * @return \Closure (x->x)
 * @sig Callable -> \Closure (x->x)
 */
function tap(callable $callable)
{
  return function ($value) use ($callable) {
    call_user_func($callable, $value);
    return $value;
  };
}
const tap = __NAMESPACE__ . '\tap';
