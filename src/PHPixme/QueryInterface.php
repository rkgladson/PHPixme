<?php

namespace PHPixme;

/**
 * Interface QueryInterface
 * @package PHPixme
 */
interface QueryInterface
{
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
}