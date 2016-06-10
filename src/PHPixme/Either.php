<?php
namespace PHPixme;
/**
 * Class Either
 * Either is a Exclusive Disjunction of Collections, and therefore its children are not Transversable Collections
 * Attempting to directly map across a disjunction will result in a type error.
 * @package PHPixme
 */
abstract class Either implements
  TypeInterface
  , UnaryApplicativeInterface
  , UnbiasedDisjunctionInterface
  , UnaryApplicativeLeftDisjunctionInterface
  , FlattenLeftInterface
  , UnaryApplicativeRightDisjunctionInterface
  , FlattenRightInterface
  , SwappableDisjunctionInterface
  , DisjunctiveFoldInterface
{
  use RootTypeTrait, ClosedTrait;
  // Note: Either should not implement ::of on its own, and should leave it to its children

  /**
   * Get the value regardless of whether the class is a Left or a Right
   * @return mixed
   */
  abstract public function merge();

  /**
   * @inheritdoc
   * @return Either
   */
  abstract public function swap();

  /**
   * Projects the Either into a Maybe.
   * Returns Some if Either is subclass Left, none if subclass Right
   * @return Maybe
   */
  abstract public function left();

  /**
   * Projects the Either into a Maybe
   * Returns Some if Either is subclass Right, none if subclass Left
   * @return Maybe
   */
  abstract public function right();

  /**
   * @inheritdoc
   * @return Left
   */
  public static function ofLeft($value)
  {
    return Left::of($value);
  }

  /**
   * Flatten the contents if the class is Left
   * @return Either
   */
  abstract public function flattenLeft();


  /**
   * @inheritdoc
   * @return Right
   */
  public static function ofRight($value)
  {
    return Right::of($value);
  }

  /**
   * Flattens the contents if the Class is right.
   * @return Either
   */
  abstract public function flattenRight();

  /**
   * converts the contained track to a Exclusive left or right
   * @return Exclusive
   */
  public function toBiasedDisjunction()
  {
    return $this->isLeft()
      ? Exclusive::ofLeft($this->merge())
      : Exclusive::ofRight($this->merge());
  }
}