<?php
namespace PHPixme;

/**
 * Class ClosedTrait
 * @package PHPixme
 * Closes off getters and setters from the outside world.
 */
trait ClosedTrait
{
  /**
   * The following is not permitted:
   * @inheritdoc
   * @throws exception\MutationException
   */
  final public function __get($key)
  {
    throw new exception\MutationException();
  }

  /**
   * The following is not permitted:
   * @inheritdoc
   * @throws exception\MutationException
   */
  final public function __set($key, $value)
  {
    throw new exception\MutationException();
  }
}