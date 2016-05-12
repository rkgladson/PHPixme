<?php
namespace PHPixme;

/**
 * Y Combinator
 * This combinator provides recursion without having a name for the callable itself
 * @param callable $callbackContainer ((\Closure self)->\Closure(*->x))
 * @return mixed
 * @sig callable ((\Closure self)->\Closure(*->x)) -> \Closure (*->x)
 */
function Y(callable $callbackContainer)
{
  $g = function (\Closure $x) use ($callbackContainer) {
    return call_user_func($callbackContainer
      , function () use ($x) {
        return call_user_func_array($x($x), func_get_args());
      }
    );
  };

  return $g($g);
}
const Y = __NAMESPACE__ . '\Y';
