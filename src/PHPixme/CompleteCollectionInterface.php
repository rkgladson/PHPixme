<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 9:59 AM
 */

namespace PHPixme;

interface CompleteCollectionInterface extends
  CollectionInterface
  , FilterableInterface
  , ReduceableInterface
{
  /**
   * Combine the data sets with the container
   * @param \Traversable[]|array[]|\PHPixme\CompleteCollectionInterface[] ...$traversableR
   * @return static
   */
  // Fixme: doesn't apply to single item collections
//    public function union(...$traversableR);

}