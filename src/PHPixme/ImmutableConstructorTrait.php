<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/19/2016
 * Time: 1:47 PM
 */

namespace PHPixme;
/**
 * Class ConstructedOnlyOnce
 * This is a helper to add immutability to a constructor
 * @package PHPixme
 */
trait ImmutableConstructorTrait
{
  // Both of these need to be private, as a child could potentially
  // Call a parent constructor, hopefully by accident, each time.
  // So the state of 'calledness' is individual to each layer.
  // Sadly, this calls for more boilerplate :(
  private $onceAndOnlyOnce = false;

  /**
   * Throw an error if this method is ever called more than once.
   * Usage: the first line of __construct. PHP is a BDSM language.
   * @throws exception\MutationException
   */
  private function assertOnce() {
    if ($this->onceAndOnlyOnce) {
      throw new exception\MutationException();
    }
    $this->onceAndOnlyOnce = true;
  }
}