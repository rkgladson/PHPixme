<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/25/2016
 * Time: 2:52 PM
 */

namespace PHPixme;

/**
 * Interface SingleStaticCreation
 * Collections who's value domain is either 0 or/to 1 should implement this interface.
 * @package PHPixme
 */
interface UnaryApplicativeInterface extends ApplicativeInterface
{
  /**
   * Passes a single item value into a new instance of itself.
   * @param $item
   * @return self
   */
  public static function of($item);
}