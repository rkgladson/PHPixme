<?php

namespace PHPixme;


/**
 * Class AssertTypeTrait
 * A trait to add standard runtime type checking to a class and its children 
 * @package PHPixme
 */
trait AssertTypeTrait
{
  /**
   * Helper for return value checking.
   * @param mixed $unknown
   * @return self
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