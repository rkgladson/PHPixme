<?php
namespace PHPixme;

/**
 * Class LeftHandedTrait
 * Use in Conjunction with LeftHandedSideType 
 * @package PHPixme
 */
trait LeftHandedTrait
{
  /**
   * Returns that the type is a Left handed type
   * @return bool
   */
  final public function isLeft()
  {
    return true;
  }

  /**
   * Returns that it is not a Right handed type
   * @return bool
   */
  final public function isRight()
  {
    return false;
  }
}