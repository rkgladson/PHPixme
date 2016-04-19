<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:18 PM
 */

namespace PHPixme;


/**
 * Interface SingleCollectionInterface
 * @package PHPixme
 * @description
 * A class of items that may not contain nothing, and only can contain one item.
 */
interface SingleCollectionInterface extends CollectionInterface
{
  /**
   * @param array ...$item Please not only the first item will be taken
   * @return mixed
   * @throws \LengthException on an empty set of arguments.
   */
  static public function of($item, ...$items);

  /**
   * Create a new instance with the first item in the traversable as contents.
   * @param array|\Traversable $traversable
   * @return mixed
   * @throws \LengthException if the traversable is an empty set
   */
  static public function from($traversable);
}