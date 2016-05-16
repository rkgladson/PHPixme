<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 8:50 AM
 */

namespace PHPixme;


interface ListInterface
{
  /**
   * Get the head, the first element, of the list as a Maybe construct
   * @return Some|None
   */
  public function headMaybe();

  /**
   * Returns the head, or the fist element, of the list as that value 
   * or null if the list is empty.
   * @return mixed|null
   */
  public function head();

  /**
   * Returns a collection of itself containing all elements that are not its head
   * @return static
   */
  public function tail();

  /**
   * Returns a new ListInterface with the given remaining number elements dropped from the front of the ListInterface
   * @param int $number
   * @return static
   */
  public function drop($number);

  /**
   * Returns a new ListInterface with the given remaining number of elements dropped from the back of the ListInterface
   * @param int $number
   * @return static
   */
  public function dropRight($number);

  /**
   * Returns a new ListInterface with only the remaining given number of elements from the front of the ListInterface.
   * @param int $number
   * @return static
   */
  public function take($number);

  /**
   * Returns a new ListInterface with only the remaining number of elements from the back of the ListInterface
   * @param int $number
   * @return static
   */
  public function takeRight($number);
  
}