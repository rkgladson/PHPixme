<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 2:04 PM
 */

namespace PHPixme;

/**
 * Interface UnaryApplicativeRightDisjunctionInterface
 * Denotes that the right handed side of a Disjunction has a applicative method
 * @package PHPixme
 */
interface UnaryApplicativeRightDisjunctionInterface
{
  /**
   * Creates a new 'right hand side' DisjunctionInterface
   * @param $item
   * @return DisjunctionInterface
   */
  public static function ofRight($item);
}