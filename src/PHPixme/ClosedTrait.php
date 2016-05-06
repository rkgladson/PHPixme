<?php
namespace PHPixme;

/**
 * Class ClosedTrait
 * @package PHPixme
 * Closes off getters and setters from the outside world.
 */
trait ClosedTrait
{
  final public function __get($key)
  {
    throw new \OutOfBoundsException('You may not get dynamic properties on a closed object.');
  }

  final public function __set($key, $value)
  {
    throw new \BadMethodCallException('You may not set on dynamic properties on a closed object.');
  }
}