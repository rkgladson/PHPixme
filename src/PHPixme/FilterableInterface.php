<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:47 PM
 */

namespace PHPixme;

/**
 * Interface FilterableInterface
 * Denotes the collection is filterable to an empty representation of some kind
 * @package PHPixme
 */
interface FilterableInterface
{
  /**
   * Filters the contents if the application of $hof returns false
   * @param callable $hof ($value, $key, $container): boolean
   * @return static
   */
  public function filter(callable $hof);

  /**
   * Filters the contents if the application of $hof returns true
   * @param callable $hof ($value, $key, $container): boolean
   * @return static
   */
  public function filterNot(callable $hof);

}