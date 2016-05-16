<?php
namespace PHPixme;


trait LeftHandedTrait
{
  final public function isLeft()
  {
    return true;
  }

  final public function isRight()
  {
    return false;
  }
}