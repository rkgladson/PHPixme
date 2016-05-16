<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 2:04 PM
 */

namespace PHPixme;


interface SingleRightApplicativeDisjunctionInterface
{
  /**
   * Creates a new 'right hand side' DisjunctionInterface
   * @param $item
   * @return DisjunctionInterface
   */
  public static function ofRight($item);
}