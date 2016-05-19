<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:20 AM
 */

namespace PHPixme;

/**
 * Class Some
 * The existent value form of Maybe.
 * @package PHPixme
 */
class Some extends Maybe
{
  use ImmutableConstructorTrait;
  protected $x;

  // -- Magic Methods --
  /**
   * Some constructor.
   * @param mixed $x
   */
  public function __construct($x)
  {
    $this->assertOnce();
    $this->x = $x;
  }
// == Magic Methods ==

  /**
   * Create a new Some container with a value
   * @param mixed $head the value to contain
   * @return Some
   */
  public static function of($head)
  {
    return new static($head);
  }

  /**
   * @inheritdoc
   */
  public function contains($x)
  {
    return $this->x === $x;
  }

  /**
   * @inheritdoc
   */
  public function exists(callable $hof)
  {
    return (boolean)call_user_func($hof, $this->x);
  }
  
  /**
   * @inheritdoc
   */
  public function get()
  {
    return $this->x;
  }

  // -- Natural Transformation Interface --
  /**
   * @param callable $hof
   * @return Some|None
   */
  public function find(callable $hof)
  {
    return call_user_func($hof, $this->x, 0, $this) ? $this : None();
  }

  /**
   * @inheritdoc
   * @return Some|None
   */
  public function filter(callable $hof)
  {
    return call_user_func($hof, $this->x, 0, $this) ?
      $this
      : None::getInstance();
  }

  /**
   * @inheritdoc
   * @return Some|None
   */
  public function filterNot(callable $hof)
  {
    return !call_user_func($hof, $this->x, 0, $this) ?
      $this
      : None::getInstance();
  }

  /**
   * @inheritdoc
   * return Some|None
   */
  public function flatMap(callable $hof)
  {
    return Maybe::assertType(call_user_func($hof, $this->x, 0, $this));
  }

  /**
   * @inheritdoc
   * @return Some|None
   */
  public function flatten()
  {
    return Maybe::assertType($this->x);
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
  public function foldRight(callable $hof, $startVal)
  {
    return call_user_func($hof, $startVal, $this->get(), 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function forAll(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->x, 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function forNone(callable $predicate)
  {
    return !((boolean)call_user_func($predicate, $this->x, 0, $this));
  }

  /**
   * @inheritdoc
   */
  public function forSome(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->x, 0, $this);
  }

  /**
   * @inheritdoc
   */
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
    return static::of(call_user_func($hof, $this->x, 0, $this));
  }

  /**
   * @inheritdoc
   */
  public function toArray()
  {
    return [$this->x];
  }

  /**
   * @inheritdoc
   */
  public function walk(callable $hof)
  {
    $hof($this->get(), 0, $this);
    return $this;
  }

  // == Natural Transformation Interface ==
  // -- Countable Interface--
  /**
   * @inheritdoc
   */
  public function count()
  {
    return 1;
  }

  // == Countable Interface==

  /**
   * @inheritdoc
   */
  public function getIterator()
  {
    return new \ArrayIterator([$this->x]);
  }
}