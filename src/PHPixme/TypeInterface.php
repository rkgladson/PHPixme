<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/9/2016
 * Time: 10:03 AM
 */

namespace PHPixme;

/**
 * Interface IsAInterface
 * The basic type hierarchy interface
 * @package PHPixme
 */
interface TypeInterface
{
  /**
   * Keeps the return value contract with the root type
   * @param mixed $unknown
   * @return mixed some child of the root class
   * @throws exception\InvalidReturnException
   */
  public static function assertRootType($unknown);

  /**
   * Keeps the return value contract with itself 
   * @param mixed $unknown
   * @return static
   * @throws exception\InvalidReturnException
   */
  public static function assertType($unknown);
}