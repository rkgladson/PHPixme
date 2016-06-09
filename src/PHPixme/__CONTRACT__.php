<?php

namespace PHPixme;

use PHPixme\exception\InvalidArgumentException as invalidArgument;
use PHPixme\exception\InvalidCompositionException as invalidComposition;
use PHPixme\exception\InvalidContentException as invalidContent;
use PHPixme\exception\InvalidReturnException as invalidReturn;

/**
 * Class __CONTRACT__
 * Contract is a helper function which is used to provide consistency of exceptions thrown
 * caused by contract violations.
 * @package PHPixme
 */
abstract class __CONTRACT__
{
  /**
   * Asserts the value is a number
   * @param mixed $number
   * @param int $position
   * @return int
   * @sig x -> x
   */
  static public function argIsAPositiveOrZeroInt($number, $position = 0)
  {
    if (is_integer($number) && -1 < $number) {
      return $number;
    }
    throw new invalidArgument($number, $position, "argument $position be a integer >=0");
  }

  /**
   * @param mixed $callable
   * @param int $position
   * @return callable
   * @throws \InvalidArgumentException
   */
  static public function argIsACallable($callable, $position = 0)
  {
    if (is_callable($callable)) {
      return $callable;
    }
    throw  new invalidArgument($callable, $position, "argument $position must be a callable");
  }


  /**
   * Asserts that the input is Traversable
   * @param mixed $arrayLike
   * @param int $position
   * @return array|\Traversable
   * @throws invalidArgument
   * @sig x -> x
   */
  static public function argIsATraversable($arrayLike, $position = 0)
  {
    if (is_array($arrayLike) || $arrayLike instanceof \Traversable) {
      return $arrayLike;
    }
    throw new invalidArgument($arrayLike, $position, "argument $position must be a Traversable or array");
  }
  
  /**
   * @param mixed $composure
   * @return callable
   * @throws $invalidComposition
   */
  static public function composedIsACallable($composure)
  {
    if (is_callable($composure)) {
      return $composure;
    }
    throw new invalidComposition($composure, 'arguments did not compose to a callable');
  }

  /**
   * @param $classPath
   * @param $contents
   * @return mixed
   * @throws invalidContent
   */
  static public function contentIsA($classPath, $contents)
  {
    if ($contents instanceof $classPath) {
      return $contents;
    }
    throw new invalidContent($contents, "content was not of a $classPath type");
  }

  /**
   * @param mixed $contents
   * @return callable
   * @throws invalidContent
   */
  static public function contentIsACallable($contents)
  {
    if (is_callable($contents)) {
      return $contents;
    }
    throw new invalidContent($contents, "content was not a callable");
  }

  /**
   * Checks the return of a callback to be of the expected type
   * @param string $classPath
   * @param mixed $returnValue
   * @return mixed
   * @throws \UnexpectedValueException
   */
  static function returnIsA($classPath, $returnValue)
  {
    if ($returnValue instanceof $classPath) {
      return $returnValue;
    }
    throw new invalidReturn(
      $returnValue
      , "Expected return of $classPath, got " . __PRIVATE__::getDescriptor($returnValue)
    );
  }
  
}