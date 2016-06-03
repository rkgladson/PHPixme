<?php
namespace PHPixme;


/**
 * Interface FunctorInterface
 * Denotes that the class is a **functor** by having a map function
 * For mostly internal reasons Functors are required to be Traversable, and the safest way to do that in 
 * PHP is an IteratorAggregate.
 * For the same reason of being Traversable, Walk is essentially a map that would be thrown away.
 *   
 * @package PHPixme
 */
interface FunctorInterface extends
  \IteratorAggregate
{
  /**
   * Apply across the contents of the Functor
   * @param callable $hof ($value, $key, $container): mixed
   * @return self
   * @sig (($value, $key, $container) -> x) -> static(x)
   */
  public function map(callable $hof);

  /**
   * Preform $hof over the container
   * @param callable $hof ($value, $key, $container) : null
   * @return $this
   * @sig (($value $key $container) -> null) -> $this
   */
  public function walk(callable $hof);
  
}