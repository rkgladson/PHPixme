<?php

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
   * @return MonadInterface
   */
  public function left();
  /**
   * Projects the contents as a right biased Collection interface
   * @return MonadInterface
   */
  public function right();
  
  /**
   * Convert to an appropriate BiasedDisjunctionInterface type of the Implementer's own specification
   * @return BiasedDisjunctionInterface
   */
  public function toBiasedDisjunction();
}