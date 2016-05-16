<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 2:53 PM
 */

namespace PHPixme;


trait LeftHandedTrait
{
  final public function isLeft()
  {
    return true;
  }
  final public function isRight() {
    return false;
  }
}