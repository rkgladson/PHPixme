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
class Failure extends Attempt implements 
  LeftHandSideType
{
  use LeftHandedTrait
    , ImmutableConstructorTrait
    , NothingCollectionTrait;
  private $err;
  const shortName = 'failure';
  
  /**
   * @inheritdoc
   */
  public function get()
  {
    throw $this->err;
  }

  /**
   * @inheritdoc
   * @return \Exception
   */
  public function merge() {
    return $this->err;
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
    return Attempt::assertType($result);
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
    return Attempt::assertType($result);
  }

  /**
   * @param \Exception $exception
   */
  public function __construct(\Exception $exception)
  {
    $this->assertOnce();
    $this->err = $exception;
  }

  /**
   * @inheritdoc
   * @param \Exception $value
   * @return self
   */
  static public function of($value)
  {
    return new static($value);
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
  public function count()
  {
    return 0;
  }
}