<?php

namespace PHPixme;

/**
 * @param $value
 * @return Valid
 */
function Validate($value)
{
  return Validate::ofRight($value);
}
const Validate = __NAMESPACE__ . '\Validate';

/**
 * @param $value
 * @return Valid
 */
function Valid($value) {
  return new Valid($value);
}
const Valid = __NAMESPACE__ . '\Valid';

/**
 * @param $value
 * @param $reasons
 * @return Invalid
 */
function Invalid($value, $reasons) {
  return new Invalid($value, $reasons);
}
const Invalid = __NAMESPACE__ . '\Invalid';