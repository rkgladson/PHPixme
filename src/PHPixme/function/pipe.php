<?php
namespace PHPixme;
/**
 * @param $hofFirst
 * @param callable $hofSecond
 * @return mixed
 * @sig (Callable (* -> a) -> Unary Callable ( a -> b ), ..., Unary Callable (y -> z)) -> \Closure (*->z)
 */
function pipe(callable $hofFirst = null, callable $hofSecond = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pipe], func_get_args());
}
const pipe = __NAMESPACE__ . '\pipe';
__PRIVATE__::$instance[pipe] = __PRIVATE__::curryGiven([], 2, function ($x) {
  $pipe = func_get_args();
  foreach ($pipe as $index => $value) {
    __CONTRACT__::argIsACallable($value, $index);
  }
  $pipeTail = array_splice($pipe, 1);
  return function () use ($x, $pipeTail) {
    $acc = call_user_func_array($x, func_get_args());
    $_pipeTail = $pipeTail;
    foreach ($_pipeTail as $hof) {
      $acc = call_user_func($hof, $acc);
    }
    return $acc;
  };
});
