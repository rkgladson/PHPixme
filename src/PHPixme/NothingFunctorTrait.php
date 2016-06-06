<?php
namespace PHPixme;

/**
 * Class NothingFunctorTrait
 * @package PHPixme
 * A boilerplate for implementors of {@see FunctorInterface } that are a perceived nothing
 */
trait NothingFunctorTrait
{
  /**
   * @inheritdoc
   * @see FunctorInterface::map
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function map(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   * @see FunctorInterface::walk
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function walk(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  final public function getIterator() {
    return new \EmptyIterator();
  }
}