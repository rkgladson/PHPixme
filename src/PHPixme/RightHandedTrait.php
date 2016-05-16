<?php
namespace PHPixme;

/**
 * Class RightHandedTrait
 * To be used in conjunction with RightHandedType
 * @package PHPixme
 */
trait RightHandedTrait
{
  /**
   * Returns that it is not a left handed type
   * @return bool
   */
  final public function isLeft()
  {
    return false;
  }

  /**
   * Returns that it is a right handed type
   * @return bool
   */
  final public function isRight()
  {
    return true;
  }
}