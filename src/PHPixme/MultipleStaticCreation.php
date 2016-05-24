<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/25/2016
 * Time: 2:53 PM
 */

namespace PHPixme;

/**
 * Interface MultipleStaticCreation
 * Collections which have a (0/1) -> N value domain should implement this interface. 
 * @package PHPixme
 */
interface MultipleStaticCreation
{
  /**
   * Take a series of arguments, and represent each value into itself
   * @param $head
   * @param array ...$tail
   * @return self
   */
  public static function of($head, ...$tail);

  /**
   * Takes a transversable item and represent each value into itself
   * @param $transversable
   * @return self
   */
  public static function from($transversable);
}