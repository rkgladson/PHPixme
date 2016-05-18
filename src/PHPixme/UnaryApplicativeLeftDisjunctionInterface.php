<?php

namespace PHPixme;

/**
 * Interface UnaryApplicativeLeftDisjunctionInterface
 * The utility function for applicative left hand side creation for a disjunction
 * @package PHPixme
 */
interface UnaryApplicativeLeftDisjunctionInterface
{
  /**
   * Returns a 'left hand side' representation of a DisjunctionInterface
   * @param $item
   * @return DisjunctionInterface
   */
  public static function ofLeft($item);
}