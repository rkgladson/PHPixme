<?php
namespace PHPixme;
/**
 * trampoline
 * Provides a platform for tail recursive optimizations for recustive functions.
 * It will continue to execute the return value (a thunk) until the return result is not a \Closure.
 * @param callable $fn (*->mixed|\Closure)
 * @return \Closure (*->z)
 * @sig \Closure (\Closure (*->mixed z|\Closure y) x) -> \Closure(*->z)
 */
function trampoline(callable $fn)
{
  return function () use ($fn) {
    $result = call_user_func_array($fn, func_get_args());
    while ($result instanceof \Closure) {
      $result = $result();
    }
    return $result;
  };
}
const trampoline = __NAMESPACE__ . '\trampoline';

