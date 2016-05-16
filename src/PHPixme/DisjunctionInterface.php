<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 12:31 PM
 */

namespace PHPixme;


interface DisjunctionInterface
{
  /**
   * Returns the contained value, regardless what 'track' the class is
   * @return mixed
   */
  public function merge();

  /**
   * Returns True if the type is a "left hand side"
   * @return boolean
   */
  public function isLeft();
  /**
   * Returns True if the type is a "right hand side"
   * @return boolean
   */
  public function isRight();
  
}