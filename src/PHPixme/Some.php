<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:20 AM
 */

namespace PHPixme;

class Some extends Maybe
{
  use SingleIteratorTrait;
  protected $x;

  // -- Magic Methods --
  public function __construct($x)
  {
    $this->x = $x;
  }

  public static function of($head = null, ...$tail)
  {
    return new static($head);
  }

  public static function from($arrayLike)
  {
    $x = null;
    foreach ($arrayLike as $value) {
      $x = $value;
      break;
    }
    return new static($x);
  }

  // == Magic Methods ==

  public function contains($x)
  {
    return $this->x === $x;
  }

  public function exists(callable $hof)
  {
    return (boolean)$hof($this->x);
  }


  public function get()
  {
    return $this->x;
  }

  // -- Natural Transformation Interface --
  /**
   * @param callable $hof
   * @return \PHPixme\Some|\PHPixme\None
   */
  public function find(callable $hof)
  {
    return $hof($this->x, 0, $this) ? $this : None();
  }

  public function filter(callable $hof)
  {
    return $hof($this->x, 0, $this) ?
      $this
      : None::getInstance();
  }

  public function filterNot(callable $hof)
  {
    return !$hof($this->x, 0, $this) ?
      $this
      : None::getInstance();
  }

  public function flatMap(callable $hof)
  {
    return static::assertMaybeType($hof($this->x, 0, $this));
  }

  public function flatten()
  {
    return static::assertMaybeType($this->x);
  }

  /**
   * @inheritdoc
   */
  public function fold(callable $hof, $startVal)
  {
    return call_user_func($hof, $startVal, $this->get(), 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function foldRight(callable $hof, $startVal) {
    return call_user_func($hof, $startVal, $this->get(), 0, $this);
  }

  public function forAll(callable $predicate)
  {
    return (boolean)$predicate($this->x, 0, $this);
  }

  public function forNone(callable $predicate)
  {
    return !((boolean)$predicate($this->x, 0, $this));
  }

  public function forSome(callable $predicate)
  {
    return (boolean)$predicate($this->x, 0, $this);
  }

  public function isEmpty()
  {
    return false;
  }

  /**
   * @param callable $hof
   * @return Some
   */
  public function map(callable $hof)
  {
    return Some($hof($this->x, 0, $this));
  }

  public function toArray()
  {
    return [$this->x];
  }

  public function walk(callable $hof)
  {
    $hof($this->get(), 0, $this);
    return $this;
  }

  // == Natural Transformation Interface ==
  // -- Countable Interface--
  public function count()
  {
    return 1;
  }
  // == Countable Interface==


  // -- Iterator Interface --
  public function current()
  {
    return $this->done ? null : $this->x;
  }
  // == Iterator Interface ==

}