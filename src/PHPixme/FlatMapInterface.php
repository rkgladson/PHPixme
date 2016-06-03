<?php
namespace PHPixme;

/**
 * Interface FlatMapInterface
 * @package PHPixme
 */
interface FlatMapInterface
{
  /**
   * Apply a function over the data which must return the same family of type
   * Called 'Bind' and 'Chain' in other languages and libraries.
   * @param callable $fn ($value, $key, $container) -> static
   * @throws \UnexpectedValueException When the callback violates the contract by not returning the same type
   * @return static
   * @sig (($value, $key, $container) -> static ) -> static
   */
  public function flatMap(callable $fn);
}