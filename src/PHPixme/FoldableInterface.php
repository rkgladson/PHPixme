<?php
namespace PHPixme;

/**
 * Interface FoldableInterface
 * @package PHPixme
 * Represents a type whose contents can be sequentially accumulated from one type to another.
 */
interface FoldableInterface
{
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
   * Converts the Foldable to an array, in any structure that is appropriate within that array
   * @return array
   * @sig () -> array
   */
  public function toArray();
}