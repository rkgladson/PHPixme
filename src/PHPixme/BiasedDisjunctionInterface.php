<?php
namespace PHPixme;

/**
 * Interface BiasedDisjunctionInterface
 * Represents a Disjunction with a inherent Right bias, which upon the Collection Interface can be run.
 * @package PHPixme
 */
interface BiasedDisjunctionInterface extends
  DisjunctionInterface
{
  /**
   * Convert this biased interface into a unbiased type of the implementer's choosing
   * @return UnbiasedDisjunctionInterface
   */
  public function toUnbiasedDisjunction();
}