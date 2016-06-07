<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:37 PM
 */

namespace PHPixme;

/**
 * Interface CollectionInterface
 * Classes which implement the collection interface are sets of finite values.
 * @package PHPixme
 */
interface CollectionInterface extends
  // We make no assumption about the kind of static creation it will have, rather that it will have it
  MonadInterface
  , FoldableInterface
  , QueryInterface
{
  // This space is intentionally left blank
}