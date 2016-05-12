<?php
namespace PHPixme;
/**
 * Wrap a callable in a function that will eat all but two arguments
 * @param callable $hof
 * @return \Closure
 * @sig Callable (* -> x) -> \Closure (a, b -> x)
 */
function binary(callable $hof = null)
{
  return function ($_0, $_1) use ($hof) {
    return call_user_func($hof, $_0, $_1);
  };
}
const binary = __NAMESPACE__ . '\binary';
