<?php
namespace PHPixme;

const Either = __NAMESPACE__ . '\Either';

/**
 * Make a new Left without using the new keyword.
 * @see Left::__construct
 * @param $value
 * @return Left
 */
function Left($value)
{
  return new Left($value);
}
const Left = __NAMESPACE__ . '\Left';

/**
 * Make a new Right without using the new keyword
 * @see Right::__construct
 * @param $value
 * @return Right
 */
function Right($value)
{
  return new Right($value);
}
const Right = __NAMESPACE__ . '\Right';
