<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:48 PM
 */

namespace PHPixme;


interface ReducibleInterface
{
  /**
   * Reduce across this class
   * @param callable $hof ($prevVal, $value, $key, $container):mixed
   * @return mixed - whatever $hof returned last, or the only item contained
   * @throws \LengthException  - Throws length exceptions when the container's value contains less than 1
   */
  public function reduce(callable $hof);

  /**
   * Reduce across this class
   * @param callable $hof ($prevVal, $value, $key, $container):mixed
   * @return mixed - whatever $hof returned last, or the only item contained
   * @throws \LengthException - Throws range exceptions when the container's value contains less than 1
   */
  public function reduceRight(callable $hof);
}