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
  public static function initialize()
  {
    if (!static::$initialized) {
      static::$initialized = true;
      static::$placeholder = new \stdClass();
    }
  }

  /**
   *
   * @return \stdClass
   */
  public static function placeholder()
  {
    return static::$placeholder;
  }

  public static function getDescriptor($value)
  {
    return is_object($value)
      ? get_class($value)
      : (
      is_resource($value)
        ? get_resource_type($value)
        : gettype($value)
      );
  }

  /**
   * Asserts that the input can be used in some way by user_call_function_array
   * @param $callable
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x->x
   */
  static function assertCallable($callable)
  {
    if (is_callable($callable)) {
      return $callable;
    }
    throw new \InvalidArgumentException('callback must be a callable function');
  }

  /**
   * @param $unknown
   * @return CollectionInterface
   * @throws \UnexpectedValueException
   */
  static function assertCollection($unknown)
  {
    if ($unknown instanceof CollectionInterface) {
      return $unknown;
    }
    throw new \UnexpectedValueException(
      __PRIVATE__::getDescriptor($unknown) . ' is not a kind of ' . CollectionInterface::class
    );
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
    if (is_integer($number) && -1 < $number) {
      return $number;
    }
    throw new \InvalidArgumentException('argument must be a integer 0 or greater');
  }

  /**
   * Asserts that the input is Traversable
   * @param $arrayLike
   * @return mixed
   * @throws \InvalidArgumentException
   * @sig x -> x
   */
  public static function assertTraversable($arrayLike)
  {
    if (is_array($arrayLike) || $arrayLike instanceof \Traversable) {
      return $arrayLike;
    }
    throw new \InvalidArgumentException('argument must be a Traversable or array');
  }

  static function copyTransversable($traversable)
  {
    static::assertTraversable($traversable);
    return is_array($traversable)
      ? $traversable
      : (
      $traversable instanceof \IteratorAggregate
        ? $traversable->getIterator()
        : clone ($traversable)
      );
  }

  /**
   * Produces a thunk until a value passed to the callback.
   * @param \Closure $fn (x)->a
   * @return \Closure (x) -> a
   */
  public static function curryExactly1(\Closure $fn)
  {
    $givenOne = function ($v1 = null) use (&$givenOne, $fn) {
      return func_num_args() !== 0 && $v1 !== static::$placeholder ? $fn($v1) : $givenOne;
    };
    return $givenOne;
  }

  /**
   * Curries exactly 2 arguments
   * @param \Closure $fn (x, y) -> a
   * @return \Closure (x)->(y)->a
   * @sig ((x,y)->a) -> (x)-> (y) -> a
   */
  public static function curryExactly2(\Closure $fn)
  {
    $given_1_2 = function ($v1 = null, $v2 = null) use (&$given_1_2, $fn) {
      $arity = func_num_args();
      $X1 = $arity >= 1 && $v1 !== static::$placeholder;
      $_2 = $arity < 2 || $v2 === static::$placeholder;
      if ($X1 && !$_2) { // Both defined
        // Common, and a valuable elimination.
        return $fn($v1, $v2);
      }
      if (!$X1 && $_2) { // All undefined
        // Rare, but a valuable elimination later.
        return $given_1_2;
      }
      // Common, the user is giving just the first param.
      if ($X1) {
        // $v1 must be defined because $v2 is undefined
        $givenX1_2 = function ($v2 = null) use ($fn, $v1, &$givenX1_2) {
          return func_num_args() !== 0 && $v2 !== static::$placeholder ? $fn($v1, $v2) : $givenX1_2;
        };
        return $givenX1_2;
      }
      // The user is likely giving data first.
      // $v2 must be defined, and $v1 must be undefined
      $given_1X2 = function ($v1 = null) use ($fn, $v2, &$given_1X2) {
        return func_num_args() !== 0 && $v1 !== static::$placeholder ? $fn($v1, $v2) : $given_1X2;
      };
      return $given_1X2;
    };
    return $given_1_2;
  }

  /**
   * This function curries only 3 arguments, passing it off to lower levels of curry when appropriate.
   * @param \Closure $fn (x, y, z) -> a
   * @return \Closure (x) -> (y) -> (z) -> a
   * @sig ((x, y, z) -> a) -> (x) -> (y) -> (z) -> a
   */
  public static function curryExactly3(\Closure $fn)
  {
    $given_1_2_3 = function ($v1 = null, $v2 = null, $v3 = null) use ($fn, &$given_1_2_3) {
      $arity = func_num_args();
      // !$_1 is such a common case when using FP, that it has been pre-optimized to assume it is not undefined
      $X1 = $arity >= 1 && $v1 !== static::$placeholder;
      /// The rest are about as common as each other.
      $_2 = $arity < 2 || $v2 === static::$placeholder;
      $_3 = $arity < 3 || $v3 === static::$placeholder;
      if ($X1 && !$_2 && !$_3) { // Eliminate all defined
        return $fn($v1, $v2, $v3);
      }
      if (!$X1 && $_2 && $_3) { // Eliminate all undefined
        // Rare, but its a valuable elimination later
        return $given_1_2_3;
      }
      if ($X1) {
        // $v1 is known to be defined
        if ($_2 && $_3) { // $v2 and $v3 are undefined, a common case.
          return static::curryExactly2(function ($v2, $v3) use ($fn, $v1) {
            return $fn($v1, $v2, $v3);
          });
        }
        // We now know either $v2 or $v3 is undefined.
        if ($_3) {
          // More often than not, two arguments are given. So check if the last is empty.
          // $v2 must be defined because both are not undefined.
          $givenX1X2_3 = function ($v3 = null) use ($fn, $v1, $v2, &$givenX1X2_3) {
            return func_num_args() !== 0 && $v3 !== static::$placeholder ? $fn($v1, $v2, $v3) : $givenX1X2_3;
          };
          return $givenX1X2_3;
        }
        //$v3 is defined, therefore $v2 must be undefined.
        $givenX1_2X3 = function ($v2 = null) use ($fn, $v1, $v3, &$givenX1_2X3) {
          return func_num_args() !== 0 && $v2 !== static::$placeholder ? $fn($v1, $v2, $v3) : $givenX1_2X3;
        };
        return $givenX1_2X3;

      }
      // $v1 is known to be undefined. User is going down the unhappy path.
      // Maybe they are trying to give the data first? Some habits are hard to break.
      if ($_2) {
        // We know that $v1 and $v2 are undefined, and they cannot all be undefined, therefor $v3 is defined.
        return static::curryExactly2(function ($v1, $v2) use ($fn, $v3) {
          return $fn($v1, $v2, $v3);
        });
      }
      // The user has made some... interesting choices. Both very rare cases.
      // We knew $v1 is undefined, and now $v2 must be defined
      if ($_3) { // $v3 is a known undefined
        return static::curryExactly2(function ($v1, $v3) use ($fn, $v2) {
          return $fn($v1, $v2, $v3);
        });
      }
      // $v2 and $v3 are defined
      $given_1X2X3 = function ($v1 = null) use ($fn, $v2, $v3, &$given_1X2X3) {
        return func_num_args() !== 0 && $v1 !== static::$placeholder ? $fn($v1, $v2, $v3) : $given_1X2X3;
      };
      return $given_1X2X3;

    };

    return $given_1_2_3;
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