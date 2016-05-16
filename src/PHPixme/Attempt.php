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
  BiasedDisjunctionInterface
  , SingleStaticCreation
  , \Countable
{
  use AssertTypeTrait, ClosedTrait;

  /**
   * @param callable $value
   * @return Failure|Success
   * @throws \InvalidArgumentException if $value is not a callable
   */
  public static function of($value)
  {
    return Attempt(__PRIVATE__::assertCallable($value));
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
    return Attempt::assertType($result);
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
   * Converts the value of success or failure to an indexed array with the instance type pointing to value
   * @return array
   */
  public function toArray()
  {
    try {
      return ['success' => $this->get()];
    } catch (\Exception $e) {
      return ['failure' => $e];
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
  public function toUnbiasedDisjunctionInterface()
  {
    return $this->isFailure() ? Left::of($this->merge()) : Right::of($this->merge());
  }
}

