<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 12:25 PM
 */

namespace PHPixme;

/**
 * Interface BiasedDisjunctionInterface
 * Represents a Disjunction with a inherent Right bias, which upon the Collection Interface can be run.
 * @package PHPixme
 */
interface BiasedDisjunctionInterface extends
  CollectionInterface
  , DisjunctionInterface
{
  /**
   * Convert this biased interface into a unbiased type of the implementer's choosing
   * @return UnbiasedDisjunctionInterface
   */
  public function toUnbiasedDisjunctionInterface();
}