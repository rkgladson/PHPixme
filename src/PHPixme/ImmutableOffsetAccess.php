<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/3/2016
 * Time: 11:35 AM
 */

namespace PHPixme;

/**
 * Interface ImmutableOffsetAccess
 * This is an immutable deviation of the mutable implementation of ArrayAccess
 * @package PHPixme
 */
interface ImmutableOffsetAccess
{
  /**
   * Returns true or false if the offset exists in the collection
   * @param mixed $offset
   * @return boolean
   */
  public function offsetExists($offset);

  /**
   * gets the value at an offset within the collection.
   * Please note, that unlike ArrayAccess::offsetGet, it does not return a reference, only a value.
   * Use offsetSet if you must make collection modifications.
   * @param mixed $offset
   * @return mixed|null
   */
  public function offsetGet($offset);

  /**
   * Sets a value at offset on a copy of the collection, and returns that collection.
   * When $offset is null, it will push the value to the end of the copied collection.
   * @param mixed $offset
   * @param mixed $value
   * @return static
   */
  public function offsetSet($offset, $value);

  /**
   * Unset the offset on a copy of the collection and returns the omitting collection.
   * @param mixed $offset
   * @return static
   */
  public function offsetUnset($offset);

  /**
   * A convince adapter that might expect a mutable array object,
   * since ImmutableOffsetAccess cannot implement the ArrayAccess Interface due
   * to their slight differences.
   * @return \ArrayObject
   */
  public function toArrayObject();

}