<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:37 PM
 */

namespace PHPixme;

/**
 * Interface CollectionInterface
 * Classes which implement the collection interface are sets of finite values.
 * @package PHPixme
 */
interface CollectionInterface extends
  // We make no assumption about the kind of static creation it will have, rather that it will have it
  ApplicativeInterface
  , ApplyInterface
  , FunctorInterface
  , FlatMapInterface
  // Collections should not store their own iteration state, but rather delegate it to another object
  
{

  // -- application --
  
  /**
   * Fold across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   * @sig ((callable (a, b)-> a , a) -> a
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
   * @return self
   * @throws \UnexpectedValueException if the data-set could not be flattened
   * @sig () -> self
   */
  public function flatten();

  // == application ==

  // -- Query --

  /**
   * Is the container empty?
   * @return bool
   */
  public function isEmpty();

  /**
   * Search the container
   * @param callable $hof ($value, $key, $container):boolean
   * @return Maybe
   */
  public function find(callable $hof);

  /**
   * Checks to see if the $predicate applies true to all within a container
   * on empty collections, forAll will return true (a vacuous truth)
   * Eg:"I ate all my vegetables." on a plate that had no vegetables to begin with is as such.
   * @param callable $predicate ($value, $key, $container): boolean
   * @return boolean
   * @sig (($value key $container)->boolean) -> boolean
   */
  public function forAll(callable $predicate);

  /**
   * Checks to see if the $predicate applies true to none within a container
   * On empty collections, forNone will return true
   * Eg:"There are not any vegetables on my plate." is true on a plate that began empty.
   * @param callable $predicate ($value, $key, $container): boolean
   * @return boolean
   * @sig (($value $key $container)-> boolean) -> boolean
   */
  public function forNone(callable $predicate);

  /**
   * Checks to see if the $predicate applies true to at least one within a container
   * on empty collections, forSome will return false.
   * Eg: "There is a vegetable on my plate" On a empty plate is blatantly false.
   * @param callable $predicate ($value, key, $container): boolean
   * @return boolean
   * @sig (($value $key $container)-> boolean) -> boolean
   */
  public function forSome(callable $predicate);

  // == Query ==
  
  // -- Conversion --
  /**
   * Converts the container to an array, in any structure that is appropriate within that array
   * @return array
   * @sig () -> array
   */
  public function toArray();
  // == Conversion ==
}