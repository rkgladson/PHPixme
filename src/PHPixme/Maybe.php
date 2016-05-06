<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:19 AM
 */

namespace PHPixme;
/**
 * Class Maybe
 * Maybe is a superclass of Some and None, designed to deal with nulls without
 * having to check for them. Maybe will produce a None if the stored item is null, 
 * and Some if the value is not null.
 * @package PHPixme
 */
abstract class Maybe implements 
  CollectionInterface
  , SingleStaticCreation
  , FilterableInterface
  , ReducibleInterface
  , \Countable
{
  use AssertTypeTrait, ClosedTrait;
  static function of($head = null)
  {
    return Maybe($head);
  }
  
  /**
   * @param mixed $x - The value being checked inside
   * @return boolean - whether or not this contains it
   */
  abstract public function contains($x);

  /**
   * @param callable $hof ($value): boolean
   * @return mixed
   */
  abstract public function exists(callable $hof);


  /**
   * Gets whatever is contained
   * @return mixed - The value contained in subclass \Some
   * @throws \Exception - if of subclass None
   */
  abstract public function get();


  /**
   * If the Maybe is none, return default, else return contents
   * @param mixed $default
   * @return mixed
   */
  public function getOrElse($default)
  {
    return $this->isEmpty() ? $default : $this->get();
  }

  /**
   * Does the Maybe contain a value?
   * @return bool
   */
  final public function isDefined()
  {
    return !$this->isEmpty();
  }

  public function orNull()
  {
    return $this->getOrElse(null);
  }

  /**
   * @param callable $hof () : Maybe - The default value
   * @return Maybe
   * @throws \Exception
   */
  public function orElse(callable $hof)
  {
    if ($this->isEmpty()) {
      return Maybe::assertType(call_user_func($hof));
    }
    return $this;
  }

  /**
   * Transforms a Maybe to a Seq
   * @return Seq
   */
  public function toSeq()
  {
    return Seq($this->toArray());
  }

  /**
   * Converts a Some to a Left, else it becomes a Right containing the return of $right.
   * @param callable $right a function holder for some alternative value
   * @return Left|Right
   */
  public function toLeft(callable $right) {
    return $this->isEmpty() ? Right(call_user_func($right)) : Left($this->get());
  }

  /**
   * Converts Some to a Right, else it becomes a Left containing the return of $left
   * @param callable $left a function holder for some alternative value
   * @return Left|Right
   */
  public function toRight(callable $left) {
    return $this->isEmpty() ? Left(call_user_func($left)) : Right($this->get());
  }

  /**
   * This form of reduce simply will return get. This will throw an error on None,
   * Since this undefined behavior to reduce on a empty collection
   * @param callable $hof
   * @return mixed
   */
  public function reduce(callable $hof)
  {
    return $this->get();
  }

  /**
   * This form of reduceRight simply will return get. This will throw an error on None,
   * Since this undefined behavior to reduceRight on a empty collection
   * @param callable $hof
   * @return mixed
   */
  public function reduceRight(callable $hof)
  {
    return $this->get();
  }
}

