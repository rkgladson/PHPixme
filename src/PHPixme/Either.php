<?php
namespace PHPixme;
/**
 * Class Either
 * Either is a Exclusive Disjunction of Collections, and therefore its children are not Transversable Collections
 * Attempting to directly map across a disjunction will result in a type error.
 * @package PHPixme
 */
abstract class Either implements
  SingleStaticCreation
  , UnbiasedDisjunctionInterface
  , SwappableDisjunctionInterface
{
  use AssertTypeTrait, ClosedTrait;

  // Note: Either should not implement ::of on its own, and should leave it to its children

  /**
   * Get the value regardless of whether the class is a Left or a Right
   * @return mixed
   */
  abstract public function merge();


  /**
   * @inheritdoc
   */
  abstract public function fold(callable $leftFn, callable $rightFn);

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
   * Returns True if the type is a Left
   * @return boolean
   */
  abstract public function isLeft();

  /**
   * Flatten the contents if the class is Left
   * @return Either
   */
  abstract public function flattenLeft();


  /**
   * Returns true if the type is a Right
   * @return boolean
   */
  abstract public function isRight();

  /**
   * Flattens the contents if the Class is right.
   * @return Either
   */
  abstract public function flattenRight();
  
  final public function toBiasedDisJunctionInterface()
  {
    //TODO: Implement a class that fits this need
    throw new \Exception('Currently unimplemented');
  }
}