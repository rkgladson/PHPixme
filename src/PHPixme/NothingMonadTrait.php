<?php
namespace PHPixme;

/**
 * Class NothingModnadTrait
 * @package PHPixme
 */
trait NothingMonadTrait
{
  use NothingFunctorTrait
    , NothingApplyTrait
    , NothingFlatMapTrait;
  // This space is purposely and ironically left blank.
}