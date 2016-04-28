<?php

namespace PHPixme;


trait AssertType
{
  /**
   * Helper for return value checking.
   * @param mixed $unknown
   * @return static
   * @throws \UnexpectedValueException when it is not a kind of self
   */
  static public function assertType($unknown)
  {
    if ($unknown instanceof static) {
      return $unknown;
    }
    throw new \UnexpectedValueException(
      __PRIVATE__::getDescriptor($unknown) . ' is not a kind of ' . static::class
    );
  }
}