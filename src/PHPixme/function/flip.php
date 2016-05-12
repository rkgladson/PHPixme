<?php
namespace PHPixme;

/**
 * Takes a callable, then flips the two next arguments before calling the function
 * @param callable
 * @return \Closure f(a, b, ....z) -> f(b,a, ... z)
 */
function flip(callable $hof)
{
  // Use the variadic internal static form of curry. We don't want to eat the rest of the args.
  return __PRIVATE__::curryGiven([], 2, function (...$args) use ($hof) {
    $temp = $args[0];
    $args[0] = $args[1];
    $args[1] = $temp;
    return call_user_func_array($hof, $args);
  });
}
const flip = __NAMESPACE__ . '\flip';
