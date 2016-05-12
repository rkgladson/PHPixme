<?php
namespace PHPixme;
/**
 * toClosure
 * takes an everyday callable and converts it to a Closure
 * @param callable $fn (*->x)
 * @return \Closure (*->x)
 * @throws \InvalidArgumentException
 * @sig callable (*->x) -> \Closure (*->x)
 */
function toClosure($fn)
{
  __PRIVATE__::assertCallable($fn);

  return $fn instanceOf \Closure
    ? $fn
    : function () use ($fn) {
      return call_user_func_array($fn, func_get_args());
    };
}
const toClosure = __NAMESPACE__ . '\toClosure';
