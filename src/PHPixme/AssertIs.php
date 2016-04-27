<?php

namespace PHPixme;


trait AssertIs
{
  /**
   * @param mixed $unknown
   * @return static
   * @throws \UnexpectedValueException when it is not a kind of self
   */
  static public function assertIs($unknown)
  {
    if ($unknown instanceof static) {
      return $unknown;
    }
    throw new \UnexpectedValueException(
      (
      is_object($unknown)
        ? get_class($unknown)
        : gettype($unknown)
      ) . ' is not a kind of ' . static::class
    );
  }
}