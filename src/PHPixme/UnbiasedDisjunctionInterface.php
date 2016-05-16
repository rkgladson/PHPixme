<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 12:35 PM
 */

namespace PHPixme;

/**
 * Interface UnbiasedDisjunctionInterface
 * Denotes an unbiased Disjunction, which is not a collection.
 * @package PHPixme
 */
interface UnbiasedDisjunctionInterface extends DisjunctionInterface
{
  /**
   * Returns the contents as a left biased Collection interface
   * @return CollectionInterface
   */
  public function left();
  /**
   * Projects the contents as a right biased Collection interface
   * @return CollectionInterface
   */
  public function right();
  /**
   * Fold over both potential 'left' and 'right' classes, producing the output of each function
   * matching against what is determined to be which child class is 'left' or 'right'
   * @param callable $leftFn (x->a) Used by 'Left'
   * @param callable $rightFn (x->b) used by 'Right'
   * @return mixed
   * @sig ((x->a), (x->b)) -> a or b
   */
  public function fold(callable $leftFn, callable $rightFn);

  /**
   * @param callable $leftFn
   * @param callable $rightFn
   * @return static
   */
  public function map(callable $leftFn, callable $rightFn);

  /**
   * Convert to an appropriate BiasedDisjunctionInterface type of the Implementer's own specification
   * @return BiasedDisjunctionInterface
   */
  public function toBiasedDisJunctionInterface();
}