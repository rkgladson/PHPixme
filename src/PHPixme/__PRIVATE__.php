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
 */
class __PRIVATE__
{
  /**
   * @var array Closure instances can be safely placed inside the array
   */
  public static $instance = [];
  private static $placeholder;
  private static $initialized = false;

  /**
   * Define internal static values.
   * @codeCoverageIgnore
   */
  public static function initialize() {
    if (!static::$initialized) {
      static::$initialized = true;
      static::$placeholder = new \stdClass();
    }
  }

  /**
   *
   * @return \stdClass
   */
  public static function placeholder () {
    return static::$placeholder;
  }


  /**
   * Asserts that the input can be used in some way by user_call_function_array
   * @param $callable
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x->x
   * @codeCoverageIgnore
   */
  static function assertCallable($callable)
  {
    if (!is_callable($callable)) {
      throw new \InvalidArgumentException('callback must be a callable function');
    }
    return $callable;
  }

  /**
   * @param $unknown
   * @return CollectionInterface
   * @throws \UnexpectedValueException
   */
  static function assertCollection($unknown)
  {
    if (!($unknown instanceof CollectionInterface)) {
      throw new \UnexpectedValueException('Return value was not implimentor of Collection!');
    }
    return $unknown;
  }

  /**
   * Asserts the value is a number
   * @param $number
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x -> x
   * @codeCoverageIgnore
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
   * @codeCoverageIgnore
   */
  public static function assertTraversable($arrayLike)
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
   * @param $firstPlaceholder int
   * @return \Closure (...args) -> \Closure|$callable()
   */
  static function curryGiven($prevArgs, $arity, $callable, $firstPlaceholder = -1)
  {
    return function () use ($arity, $callable, $prevArgs, $firstPlaceholder) {

      $prevArgsLength = count($prevArgs);

      $newArgs = func_get_args();
      $newArgsLength = count($newArgs);
      // Kickstart the process from the last known placeholder location
      $outputArgs = $firstPlaceholder < 0 ? $prevArgs : array_slice($prevArgs, 0, $firstPlaceholder);
      // Mark how many have been inserted before picking up where we left off.
      $left = $arity - count($outputArgs);
      // note the new placeholder's possible location
      $nextFirstPlaceholder = -1;
      for (
        $outputIdx = count($outputArgs), $newArgIdx = 0;
        $outputIdx < $prevArgsLength || $newArgIdx < $newArgsLength;
        $outputIdx += 1
      ) {
        if (
          (
            // The present index isn't a placeholder
            $outputIdx < $prevArgsLength
            && $prevArgs[$outputIdx] !== static::$placeholder
          )
          // There are none left to take
          || $newArgIdx >= $newArgsLength
        ) {
          // Take from the previous arguments
          $cell = $prevArgs[$outputIdx];
        } else {
          // Take from the newly given arguments
          $cell = $newArgs[$newArgIdx];
          $newArgIdx += 1;

        }
        $outputArgs[$outputIdx] = $cell;

        if ($cell !== static::$placeholder) {
          $left -= 1;
        } else if ($nextFirstPlaceholder < 0) {
          // Note the location of the first placeholder for next time.
          $nextFirstPlaceholder = $outputIdx;
        }
      }
      return ($left < 1
        ? call_user_func_array($callable, $outputArgs)
        : self::curryGiven($outputArgs, $arity, $callable, $nextFirstPlaceholder)
      );
    };
  }

  /**
   * Starts off the currying process for a giveni function
   * @param int $arity
   * @param callable $callable
   * @return \Closure
   * @throws \InvalidArgumentException when $callable is not a callable
   */
  static function curry($arity = 0, callable $callable)
  {
    self::assertPositiveOrZero($arity);
    self::assertCallable($callable);

    return self::curryGiven([], $arity, $callable);
  }

  // -- Magic Methods --
  /**
   * @codeCoverageIgnore
   */
  protected function __clone()
  {
    // This space intentionally left blank
  }

  /**
   * @codeCoverageIgnore
   */
  protected function __wakeup()
  {
    // This space intentionally left blank
  }

  /**
   * None constructor.
   * @codeCoverageIgnore
   */
  protected function __construct()
  {
    // This space intentionally left blank
  }
  // == Magic Methods ==

}

__PRIVATE__::initialize();