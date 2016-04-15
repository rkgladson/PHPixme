<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:37 PM
 */

namespace PHPixme;


interface CollectionInterface extends \Iterator
{
  /**
   * Transfer the data from one container to another
   * @param \Traversable|array $traversable
   * @return static - A new instance of the class
   */
  public static function from($traversable);
  
  /**
   * A static function that accepts a list of items that will be contained by the class
   * Note: a implementing class will discard any excess if there is a container limit
   * @param array ...$items - A list of items, defining like Array
   * @return static - A new instance of the class
   */
  static function of($items);
  /**
   * Map across the container
   * @param callable $hof ($value, $key, $container): mixed
   * @return static
   */
  public function map(callable $hof);

  /**
   * Fold across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   */
  public function fold(callable $hof, $startVal);
  /**
   * Fold Right across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   */
  public function foldRight(callable $hof, $startVal);

  /**
   * @param callable $hof ($value, $key, $container):static
   * @return static
   * @throws \Exception - if the data type returned by callback wasn't its kind
   */
  public function flatMap(callable $hof);

  /**
   * @return self
   * @throws \Exception if the data-set could not be flattened
   */
  public function flatten();

  /**
   * Checks to see if the $predicate applies true to all within a container
   * @param callable $predicate ($value, $key, $container): boolean
   * @return boolean
   */
  public function forAll(callable $predicate);

  /**
   * Checks to see if the $predicate applies true to none within a container
   * @param callable $predicate ($value, $key, $container): boolean
   * @return boolean
   */
  public function forNone(callable $predicate);

  /**
   * Checks to see if the $predicate applies true to at least one within a container
   * @param callable $predicate ($value, key, $container): boolean
   * @return boolean
   */
  public function forSome(callable $predicate);


  /**
   * Preform $hof over the container
   * @param callable $hof ($value, $key, $container) : null
   * @return $this
   */
  public function walk(callable $hof);

  /**
   * Converts the container to an array, in any structure that is appropriate within that array
   * @return array
   */
  public function toArray();
  
  /**
   * Is the container empty?
   * @return bool
   */
  public function isEmpty();

  /**
   * Search the container
   * @param callable $hof ($value, $key, $container):boolean
   * @return \PHPixme\Some|\PHPixme\None
   */
  public function find(callable $hof);
}