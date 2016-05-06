<?php
namespace PHPixme;

abstract class Either implements SingleStaticCreation
{
  use AssertTypeTrait, ClosedTrait;
  /**
   * Get the value regardless of whether the class is a Left or a Right
   * @return mixed
   */
  abstract public function merge();

  /**
   * Fold over both potential left and right values, producing the output of each function
   * determined by whether the Either is Left or a Right
   * @param callable $leftFn (x->a) Used by Left
   * @param callable $rightFn (x->b) used by Right
   * @return mixed
   * @sig ((x->a), (x->b)) -> a or b
   */
  abstract public function fold(callable $leftFn, callable $rightFn);

  /**
   * Converts a Left to a Right, or a Right to a left
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

}