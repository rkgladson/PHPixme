<?php

namespace PHPixme;

/**
 * A Right hand biased Either
 * Unlike Either, it is a Collection, and all operations are done over the right hand side child class Preferred.
 * Class Exclusive
 * @package PHPixme
 */
abstract class Exclusive implements
  BiasedDisjunctionInterface
  , CollectionInterface
  , SwappableDisjunctionInterface
  , UnaryApplicativeInterface
  , UnaryApplicativeRightDisjunctionInterface
  , FlattenRightInterface
  , UnaryApplicativeLeftDisjunctionInterface
  , FlattenLeftInterface
  , \Countable
{
  use ClosedTrait, AssertTypeTrait;
  const shortName = 0;
  
  /**
   * Create the biased right handed creation.
   * @param $item
   * @return DisjunctionInterface
   */
  public static function of($item)
  {
    return static::ofRight($item);
  }

  /**
   * @inheritdoc
   * @return Preferred
   */
  public static function ofRight($item)
  {
    return new Preferred($item);
  }
  
  /**
   * @inheritdoc
   * @return Undesired
   */
  public static function ofLeft($item)
  {
    return new Undesired($item);
  }
  
  /**
   * Converts a Exclusive to an Either
   * @return Right|Left
   */
  abstract public function toUnbiasedDisjunction();

  /**
   * @inheritdoc
   * @see Preferred::flatten
   * @return self
   */
  final public function flattenRight()
  {
    return $this->flatten();
  }

  /**
   * Returns the contained value at { @see static::shortName ::shortName } offset
   * @return array
   */
  abstract public function toArray();
}