<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:09 AM
 */

namespace PHPixme;

/**
 * Class Failure
 * @package PHPixme
 * Encloses an exception from a Attempt block, allowing it to be recovered from.
 * It will ignore any attempts to apply new success behaviors to the error state
 * contained within prior to any recovery attempts.
 */
class Failure extends Attempt
{
  private $err;

  /**
   * @inheritdoc
   */
  public function get()
  {
    throw $this->err;
  }

  /**
   * @inheritdoc
   */
  public function filter(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function flatMap(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function flatten()
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function failed()
  {
    return Success($this->err);
  }

  /**
   * @inheritdoc
   */
  function isFailure()
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  function isSuccess()
  {
    return false;
  }

  /**
   * @inheritdoc
   */
  public function map(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function recover(callable $rescueException)
  {
    return Attempt(function () use ($rescueException) {
      return call_user_func($rescueException, $this->err, $this);
    });
  }

  /**
   * @inheritdoc
   */
  public function recoverWith(callable $hof)
  {
    try {
      $result = call_user_func($hof, $this->err, $this);
    } catch (\Exception $e) {
      return Failure($e);
    }
    return static::assertAttemptType($result);
  }

  /**
   * @inheritdoc
   */
  public function transform(callable $success, callable $failure)
  {
    try {
      $result = call_user_func($failure, $this->err, $this);
    } catch (\Exception $e) {
      return Failure($e);
    }
    return static::assertAttemptType($result);
  }

  /**
   * @inheritdoc
   */
  public function walk(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function __construct(\Exception $exception)
  {
    $this->err = $exception;
  }

  /**
   * @inheritdoc
   */
  public function getIterator()
  {
    return new \EmptyIterator();
  }


  /**
   * @inheritdoc
   */
  public function fold(callable $hof, $startVal)
  {
    return $startVal;
  }

  /**
   * @inheritdoc
   */
  public function foldRight(callable $hof, $startVal)
  {
    return $startVal;
  }

  /**
   * @inheritdoc
   */
  public function isEmpty()
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function find(callable $hof)
  {
    return None();
  }

  /**
   * @inheritdoc
   */
  public function forAll(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function forNone(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function forSome(callable $predicate)
  {
    return false;
  }

  /**
   * @inheritdoc
   */
  public function count()
  {
    return 0;
  }
}