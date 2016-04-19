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
 * @package PHPixme
 * @description CompleteCollectionInterface is a class of collections with 
 * a empty object patern (which may be its own object). 
 * Complete Collections must be able to safely handle operation on the entier domain,
 * including the empty set.
 */
interface CompleteCollectionInterface extends
  CollectionInterface
  , FilterableInterface
  , ReduceableInterface
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
  // Fixme: doesn't apply to single item collections
//    public function union(...$traversableR);

}