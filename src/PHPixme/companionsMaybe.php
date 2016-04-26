<?php
namespace PHPixme;
const Maybe = __NAMESPACE__ . '\Maybe';
/**
 * Takes a value and wraps it in a Maybe family object
 * @param $x - the maybe existing value
 * @return None|Some
 */
function Maybe($x = null) {
  return func_num_args() < 1 || is_null($x)
  ? None::getInstance()
  : new Some ($x);
}

const None = __NAMESPACE__ . '\None';
/**
 * @return None
 */
function None()
{
  return None::getInstance();
}

const Some = __NAMESPACE__ . '\Some';
/**
 * @param $x - a non- null value
 * @return Some
 */
function Some($x)
{
  return new Some($x);
}