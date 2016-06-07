<?php
namespace PHPixme;

/**
 * Class NothingCollectionTrait
 * A boilerplate for implementors of { @see CollectionInterface } that are a perceived nothing
 * @package PHPixme
 */
trait NothingCollectionTrait
{
  use NothingMonadTrait
    , NothingFoldTrait
    , NothingQueryTrait;
  // This space is purposely and ironically left blank.
}