<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/6/2016
 * Time: 11:09 AM
 */

namespace PHPixme;

/**
 * Class Success
 * @package PHPixme
 * Contains the results of a successful Attempt block, allowing for successful
 * behaviors prior to the block to be executed.
 */
class Success extends Attempt implements
  RightHandSideType

{
  use RightHandedTrait
    , ImmutableConstructorTrait;
  private $value = null;
  const shortName = 'success';

  /**
   * @param mixed $value the success value
   */
  public function __construct($value)
  {
    $this->assertOnce();
    $this->value = $value;
  }

  /**
   * @inheritdoc
   * @param mixed $value
   * @return Success
   */
  static public function of($value)
  {
    return new static($value);
  }

  /**
   * @inheritdoc
   */
  public function get()
  {
    return $this->value;
  }

  /**
   * @inheritdoc
   */
  public function merge()
  {
    return $this->value;
  }

  /**
   * @inheritdoc
   */
  public function filter(callable $hof)
  {
    try {
      return call_user_func($hof, $this->value, 0, $this) ?
        $this
        : Failure(new \Exception('$value did not meet criteria.'));
    } catch (\Exception $e) {
      return Failure($e);
    }
  }

  /**
   * @inheritdoc
   */
  public function flatMap(callable $hof)
  {
    try {
      $result = $hof($this->value, 0 , $this);
    } catch (\Exception $e) {
      return Failure($e);
    }
    return static::assertRootType($result);
  }
  
  /**
   * @inheritdoc
   * @return self
   */
  public function flatten()
  {
    return __CONTRACT__::contentIsA(static::rootType(), $this->value);
  }

  /**
   * @inheritdoc
   */
  public function failed()
  {
    return Failure(new \Exception('Success.failed is an unsupported action.'));
  }

  /**
   * @inheritdoc
   */
  public function isFailure()
  {
    return false;
  }

  /**
   * @inheritdoc
   */
  public function isSuccess()
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function map(callable $hof)
  {
    return new static(call_user_func($hof, $this->value, 0, $this));
  }

  /**
   * @inheritdoc
   */
  public function apply(FunctorInterface $functor)
  {
    return $functor->map(__CONTRACT__::contentIsACallable($this->value));
  }

  /**
   * @inheritdoc
   */
  public function recover(callable $rescueException)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function recoverWith(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function transform(callable $success, callable $failure)
  {
    try {
      $result = call_user_func($success, $this->value, $this);
    } catch (\Exception $e) {
      return Failure($e);
    }
    return static::assertRootType($result);
  }


  /**
   * @inheritdoc
   */
  public function walk(callable $hof)
  {
    call_user_func($hof, $this->value, 0, $this);
    return $this;
  }

  /**
   * Fold across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   * @sig ((callable (a, b)-> a , a) -> a
   */
  public function fold(callable $hof, $startVal)
  {
    return call_user_func($hof, $startVal, $this->value, 0, $this);
  }

  /**
   * Fold Right across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   */
  public function foldRight(callable $hof, $startVal)
  {
    return call_user_func($hof, $startVal, $this->value, 0, $this);
  }

  /**
   * Is the container empty?
   * @return bool
   */
  public function isEmpty()
  {
    return false;
  }

  /**
   * Search the container
   * @param callable $hof ($value, $key, $container):boolean
   * @return Maybe
   */
  public function find(callable $hof)
  {
    return call_user_func($hof, $this->value, 0, $this) ? Some($this->value) : None();
  }

  /**
   * @inheritdoc
   */
  public function forAll(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->value, 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function forNone(callable $predicate)
  {
    return !((boolean)call_user_func($predicate, $this->value, 0, $this));
  }

  /**
   * @inheritdoc
   */
  public function forSome(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->value, 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function getIterator()
  {
    return new \ArrayIterator([$this->value]);
  }

  /**
   * @inheritdoc
   */
  public function count()
  {
    return 1;
  }
}