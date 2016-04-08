<?php

/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/8/2016
 * Time: 10:44 AM
 */
namespace PHPixme;

  /**
   * Class __Private__
   * Used for function instances
   *
   */
/**
 * Class __PRIVATE__
 * Place globals here.
 * @internal
 * @package PHPixme
 * @codeCoverageIgnore
 */
class __PRIVATE__
{
  /**
   * @var array Closure instances can be safely placed inside the array
   */
  static $instance = [];

  /**
   * Asserts that the input can be used in some way by user_call_function_array
   * @param $callable
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x->x
   */
  static function assertCallable($callable)
  {
    if (!is_callable($callable)) {
      throw new \InvalidArgumentException('callback must be a callable function');
    }
    return $callable;
  }

  /**
   * Asserts the value is a number
   * @param $number
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x -> x
   */
  static function assertPositiveOrZero($number)
  {
    if (!is_integer($number) || $number < 0) {
      throw new \InvalidArgumentException('argument must be a integer 0 or greater');
    }
    return $number;
  }

  /**
   * Asserts that the input is Traversable
   * @param $arrayLike
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x -> x
   */
  static function assertTraversable($arrayLike)
  {
    if (!is_array($arrayLike) && !($arrayLike instanceof \Traversable)) {
      throw new \InvalidArgumentException('argument must be a Traversable or array');
    }
    return $arrayLike;
  }

  /**
   * Handles the actual currying behavior for the given function
   * @param $prevArgs
   * @param $arity
   * @param $callable
   * @return \Closure
   */
  static function curryGiven($prevArgs, $arity, $callable)
  {
    return function (...$newArgs) use ($arity, $callable, $prevArgs) {
      $args = array_merge($prevArgs, $newArgs);
      if (count($args) >= $arity) {
        return call_user_func_array($callable, $args);
      }
      return self::curryGiven($args, $arity, $callable);
    };
  }

  /**
   * Starts off the currying process for a giveni function
   * @param int $arity
   * @param callable $callable
   * @return \Closure
   * @throws \InvalidArgumentException when $callable is not a callabe
   */
  static function curry($arity = 0, callable $callable)
  {
    self::assertPositiveOrZero($arity);
    self::assertCallable($callable);

    return self::curryGiven([], $arity, $callable);
  }
}