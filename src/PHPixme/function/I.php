<?php
namespace PHPixme;
/**
 * The humble Idiot Bird, or Identity
 * A function which always returns what it is passed. 
 * Surprisingly useful when you need to put a no-operation in a chain.
 * @param mixed $x
 * @return mixed $x
 * @sig x -> x
 */
function I($x)
{
  return $x;
}
const I = __NAMESPACE__ . '\I';
