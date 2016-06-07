<?php
namespace PHPixme;

/**
 * Class NothingFlatMapTrait
 * @package PHPixme
 * A boilerplate for implementors of {@see FlatMapInterface } that are a perceived nothing
 */
trait NothingFlatMapTrait
{
  /**
   * @inheritdoc
   * @see CollectionInterface::flatMap
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function flatMap(callable $hof)
  {
    return $this;
  }
}