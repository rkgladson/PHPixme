<?php
namespace PHPixme;
/**
 * @param callable $hof
 * @param \Traversable= $traversable
 * @return \Closure|mixed
 * @sig Callable (a, b -> a) -> \Traversable[a,b] -> a
 */
function reduce(callable $hof = null, $traversable = null)
{
  return call_user_func_array(__PRIVATE__::$instance[reduce], func_get_args());
}
const reduce = __NAMESPACE__ . '\reduce';
__PRIVATE__::$instance[reduce] = __PRIVATE__::curryExactly2(function ($hof, $arrayLike) {
  __PRIVATE__::assertCallable($hof);
  if ($arrayLike instanceof ReducibleInterface) {
    return $arrayLike->reduce($hof);
  }
  __PRIVATE__::assertTraversable($arrayLike);
  if (is_array($arrayLike)) {

  }
  $iterator = is_array($arrayLike) ? new \ArrayIterator($arrayLike) : __PRIVATE__::copyTransversable($arrayLike);
  if (!$iterator->valid()) {
    throw new \LengthException('Cannot reduce on empty collections. Behaviour is undefined');
  }
  $output = $iterator->current();
  $iterator->next();
  while ($iterator->valid()) {
    $output = call_user_func($hof, $output, $iterator->current(), $iterator->key(), $arrayLike);
    $iterator->next();
  }
  return $output;
});