<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 2:51 PM
 */

namespace PHPixme;


trait RightHandedTrait
{
  final public function isLeft()
  {
    return false;
  }
  final public function isRight() {
    return true;
  }
}