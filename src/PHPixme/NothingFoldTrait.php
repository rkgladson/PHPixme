<?php

namespace PHPixme;

/**
 * Class NothingFoldTrait
 * @package PHPixme
 * A boilerplate for {@see FoldInterface} for types that a a perceived nothing.
 */
trait NothingFoldTrait
{
  /**
   * @inheritdoc
   * @see FoldInterface::fold
   * This is a no-op on perceived nothings
   * @return mixed
   */
  final public function fold(callable $hof, $startVal)
  {
    return $startVal;
  }

  /**
   * @inheritdoc
   * @see FoldInterface::foldRight
   * This is a no-op on perceived nothings
   * @return mixed
   */
  final public function foldRight(callable $hof, $startVal)
  {
    return $startVal;
  }
}