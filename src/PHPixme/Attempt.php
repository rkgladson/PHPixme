<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:03 AM
 */

namespace PHPixme;

/**
 * Class Attempt
 * Attempt is equivalent to a Try ... Catch block in a object oriented form,
 * providing a consistency to the flow of data through functions which are
 * not applied on errors unless transformed. It also provides transformations
 * to options, and share some of it's methods, such as get, and getOrElse.
 * Note: Some of these methods will throw Exceptions if their contract
 * with a callback function is not met. They will not catch these kinds of errors.
 * @package PHPixme
 */
abstract class Attempt implements
  TypeInterface
  , BiasedDisjunctionInterface
  , UnaryApplicativeLeftDisjunctionInterface
  , UnaryApplicativeRightDisjunctionInterface
  , FlattenRightInterface
  , CollectionInterface
  , UnaryApplicativeInterface
  , \Countable
{
  use RootTypeTrait, ClosedTrait;
  const shortName = 0;

  /**
   * @param callable $value
   * @return Failure|Success
   */
  public static function of($value)
  {
    return Attempt($value);
  }

  /**
   * @inheritdoc
   * @param \Throwable|\Exception $item
   * @return Failure
   */
  public static function ofLeft($item)
  {
    return new Failure($item);
  }

  /**
   * @inheritdoc
   * @return Success
   */
  public static function ofRight($item)
  {
    return new Success($item);
  }

  /**
   * @return boolean
   */
  abstract public function isFailure();

  /**
   * @return boolean
   */
  abstract public function isSuccess();

  /**
   * @return mixed - Get the value contained on success
   * @throws mixed - throws the value on failure
   */
  abstract public function get();

  /**
   * Returns the value contained on either part of the Attempt Disjunction
   * @return mixed
   */
  abstract public function merge();

  /**
   * @param $default
   * @return mixed
   */
  public function getOrElse($default)
  {
    return $this->isSuccess() ? $this->get() : $default;
  }

  /**
   * @param callable $hof (): Attempt
   * @return Attempt
   * @throws \Exception on function not returning a Attempt subtype
   */
  public function orElse(callable $hof)
  {
    if ($this->isSuccess()) {
      return $this;
    }
    try {
      $result = $hof();
    } catch (\Exception $e) {
      return Failure($e);
    }
    return static::assertRootType($result);
  }

  /**
   * Changes a Failure to a success.
   * If called on a Success, it will return a failure with an unsupported action exception
   * @return Attempt
   */
  abstract public function failed();

  /**
   * Map $hof across the success value
   * @param callable $hof ($value, $key, Success $container): static
   * @return Attempt - Success($hof($value)) or the same Failure as before
   */
  abstract public function map(callable $hof);

  /**
   * Tries to recover the error value as a success using $rescueException.
   * Has no affect on Success
   * @param callable $rescueException ($value, $container): mixed ! \Exception
   * @return Attempt - If return, type Success, If Thrown, type Failure
   */
  abstract public function recover(callable $rescueException);

  /**
   * Recovers using a $hof which returns either a Success or Failure.
   * @param callable $hof ($value, $container) : Attempt
   * @return Attempt
   * @throws \Exception When $rescueException does not return an Attempt type
   */
  abstract public function recoverWith(callable $hof);


  /**
   * Convert a Success into a failure based on the result of the callback
   * If the return is true or the container is Failure, it will return an identity
   * If the callback throws or returns false, it will produce a new Failure with the exception.
   * @param callable $hof ($value, $key, $container)-> boolean
   * @return Attempt
   */
  abstract public function filter(callable $hof);
  
  /**
   * Returns an array with the value at the { @see static::shortName ::shortName } offset
   * @return array
   */
  public function toArray()
  {
    try {
      return [$this::shortName => $this->get()];
    } catch (\Exception $e) {
      return [$this::shortName => $e];
    }
  }

  /**
   * Converts Success to a Some, Converts Failure to None
   * @return Maybe
   */
  public function toMaybe()
  {
    return $this->isSuccess() ? Some($this->get()) : None();
  }

  /**
   * @inheritdoc
   * An alias for flatten
   * @return self
   */
  final public function flattenRight()
  {
    return $this->flatten();
  }

  /**
   * Transforms a success or failure given the criteria presented,
   *      able to change the internal value or container from one Attempt to another
   * @param callable $success ($value, Success $container): Attempt
   * @param callable $failure ($value, Failure $container): Attempt
   * @return Attempt
   * @throws \Exception If either function returns a non Attempt
   */
  abstract public function transform(callable $success, callable $failure);

  /**
   * Pass the Success value into $hof
   * @param callable $hof ($value, $key, Attempt $container): null
   * @return $this
   */
  abstract public function walk(callable $hof);

  /**
   * Converts a Failure to a Left, and Success to a Right
   * @return Left|Right
   */
  public function toUnbiasedDisjunction()
  {
    return $this->isLeft() ? Either::ofLeft($this->merge()) : Either::ofRight($this->merge());
  }
}

