<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/17/2016
 * Time: 11:06 AM
 */

namespace PHPixme;

/**
 * Interface FlattenLeftInterface
 * Denotes that the left hand side can contain its own kind, and may be flattened
 * @package PHPixme
 */
interface FlattenLeftInterface
{

  /**
   * Flatten the contents if the class is 'left hand side'
   * @return DisjunctionInterface
   */
  public function flattenLeft();
}