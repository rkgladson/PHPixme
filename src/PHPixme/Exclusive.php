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
   * @inheritdoc
   */
  public function getIterator()
  {
    if ($this->isRight()) {
      yield 0 => $this->merge();
    } else {
      return;
    }
  }

  /**
   * Converts a Exclusive to an Either
   * @return Right|Left
   */
  abstract public function toUnbiasedDisjunctionInterface();

  /**
   * @inheritdoc
   * @see Preferred::flatten
   * @return static
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