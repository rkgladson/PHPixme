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
   * Retrieve the Some of an offset if it exists, and the None if it does not
   * @param mixed $offset
   * @return Maybe
   */
  public function offsetGetMaybe($offset);

  /**
   * Retrieve the Success of an offset if it exist,
   * or the Failure of a exception\VacuousOffsetException containing the offset
   * @param $offset
   * @return Success|Failure
   */
  public function offsetGetAttempt($offset);

  /**
   * Sets a value at offset on a copy of the collection, and returns that collection.
   * When $offset is null, it will push the value to the end of the copied collection.
   * @param mixed $offset
   * @param mixed $value
   * @return self
   */
  public function offsetSet($offset, $value);


  /**
   * Sets the offset to the return value of $fn on a copy of that collection.
   * Use this instead of trying to modify a return reference from offsetGet.
   * If the offset does not exist, it returns itself, not executing the function
   * @param mixed $offset
   * @param callable $fn (mixed $value, mixed $key, self $container)-> mixed $z
   * @return self
   */
  public function offsetApply($offset, callable $fn);

  /**
   * Unset the offset on a copy of the collection and returns the omitting collection.
   * @param mixed $offset
   * @return self
   */
  public function offsetUnset($offset);

  /**
   * A convince adapter that might expect a mutable ArrayAccess, of an appropriate type,
   * since ImmutableOffsetAccess cannot implement the ArrayAccess Interface due
   * to their slight differences. 
   * Note: It is up to the implementation to determine which Class is most appropriate
   * @return \ArrayAccess
   */
  public function toArrayAccess();

}