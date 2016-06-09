<?php

namespace PHPixme;

/**
 * Class AssertTypeTrait
 * A trait to add standard runtime type checking to a class and its children 
 * @package PHPixme
 */
trait TypeTrait
{
  /**
   * Ensures the return for the callback is exactly the same type as the object
   * @param mixed $unknown
   * @return self
   * @throws exception\InvalidReturnException
   */
  static public function assertType($unknown)
  {
    return __CONTRACT__::returnIsA(static::class, $unknown);
  }
}