<?php
namespace PHPixme;

// -- Kestrel --
/**
 * K or Kestrel, also known as Constant or True. 
 * It takes a initial value and returns a function which will always return that value. 
 * @param mixed $first
 * @return \Closure
 * @sig first -> ignored -> first
 */
function K($first)
{
  return function ($ignored = null) use ($first) {
    return $first;
  };
}
const K = __NAMESPACE__ . '\K';

