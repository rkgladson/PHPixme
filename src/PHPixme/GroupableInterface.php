<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/5/2016
 * Time: 2:47 PM
 */

namespace PHPixme;

/**
 * Interface GroupableInterface
 * Denotes that the collection can be grouped into two other nested collections
 * @package PHPixme
 */
interface GroupableInterface
{
  /**
   * Groups the n sized collection into groups returned by groupKey, not preserving keys
   * eg, Seq("groupKey"=>Seq(value1, value 2))
   * @param callable $fn ($value, $key, $container) -> $groupKey
   * @return static
   */
  public function group(callable $fn);

  /**
   * Groups the n sized collection into groups returned by groupKey, 
   * returning them together as an array in the order of Key, Value
   * eg, Seq(groupKey=>Seq([Key1, Value1], [Key2, Value2]))
   * @param callable $fn ($value, $key, $container) -> $groupKey
   * @return static
   */
  public function groupWithKey(callable $fn);

  /**
   * Group the n sized collection into a group of true and false, not preserving keys
   * eg, Seq("true"=>Seq(value1), "false"=>Seq(value2))
   * @param callable $fn ($value, $key, $container) -> boolean
   * @return static
   */
  public function partition(callable $fn);

  /**
   * Group the n sized collection into a group of true and false, not preserving keys
   * returning them together as an array in the order of Key, Value
   * eg, Seq("true"=>Seq([key1, value1]), "false"=>Seq([key1, value2]))
   * @param callable $fn ($value, $key, $container) -> boolean
   * @return static
   */
  public function partitionWithKey(callable $fn);
  
}