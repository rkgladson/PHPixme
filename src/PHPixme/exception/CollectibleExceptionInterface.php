<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/13/2016
 * Time: 10:40 AM
 */

namespace PHPixme\exception;
/**
 * Interface CollectibleExceptionInterface
 * Denotes that there is some way to promote the exception to the throwable collection Pot
 * @package PHPixme\exception
 */
interface CollectibleExceptionInterface
{
  /**
   * Converts this Exception to the collection exception Pot
   * @return \PHPixme\Pot
   */
  public function toPot();
}