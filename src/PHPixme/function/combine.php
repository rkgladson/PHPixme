<?php
namespace PHPixme;

/**
 * Takes two functions and has the first consume the output of the second, combining them to a single function
 * @param callable $hofSecond
 * @param callable = $hofFirst
 * @return \Closure
 * @sig (Unary Callable(y -> z), ..., Unary Callable(a -> b), Callable (*->a)) -> \Closure (* -> a)
 */
function combine(callable $hofSecond = null, callable $hofFirst = null)
{
  return call_user_func_array(__PRIVATE__::$instance[combine], func_get_args());
}
const combine = __NAMESPACE__ . '\combine';
__PRIVATE__::$instance[combine] = __PRIVATE__::curryGiven([], 2, function () {
  $combine = func_get_args();
  foreach ($combine as $idx => $hof) {
    __CONTRACT__::argIsACallable($hof, $idx);
  }
  $combineHead = end($combine);
  $combineTail = array_slice($combine, 0, -1);
  $combineTailSize = count($combineTail);
  return function () use ($combineHead, $combineTail, $combineTailSize) {
    $acc = call_user_func_array($combineHead, func_get_args());
    for ($index = $combineTailSize - 1; -1 < $index; $index -= 1) {
      $acc = call_user_func($combineTail[$index], $acc);
    }
    return $acc;
  };
});
