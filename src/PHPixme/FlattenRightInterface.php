<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/17/2016
 * Time: 11:07 AM
 */

namespace PHPixme;

/**
 * Interface FlattenRightInterface
 * Denotes the right hand side can contain itself, and may be flattened
 * @package PHPixme
 */
interface FlattenRightInterface
{
  /**
   * Flattens the contents if the class is a 'right hand side'
   * @return DisjunctionInterface
   */
  public function flattenRight();
}