<?php
namespace PHPixme;


trait RightHandedTrait
{
  final public function isLeft()
  {
    return false;
  }

  final public function isRight()
  {
    return true;
  }
}