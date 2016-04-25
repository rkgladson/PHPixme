<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 9:59 AM
 */

namespace PHPixme;

/**
 * Interface CompleteCollectionInterface
 * A grouping containers that may hold of 0->N values. The 0 group may be an instance of its more generalized case.
 * @package PHPixme
 * including the empty set.
 */
interface CompleteCollectionInterface extends
  CollectionInterface
  , FilterableInterface
  , ReducibleInterface
{

  /**
   * @param null|mixed $head
   * @param array ...$items
   * @return mixed
   */
  static function of($head = null, ...$items);
  /**
   * Combine the data sets with the container
   * @param \Traversable[]|array[]|\PHPixme\CompleteCollectionInterface[] ...$traversableR
   * @return static
   */
  //public function union(...$traversableR);
}