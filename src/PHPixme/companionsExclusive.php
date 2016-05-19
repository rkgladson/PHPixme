<?php
namespace PHPixme;

const Exclusive = Exclusive::class;

/**
 * Create a new preferred instance
 * @param $value
 * @return Preferred
 */
function Preferred($value)
{
  return new Preferred($value);
}
const Preferred = Preferred::class;

/**
 * Creates a new undesired instance
 * @param $value
 * @return Undesired
 */
function Undesired ($value) {
  return new Undesired($value);
}
const Undesired = Undesired::class;
