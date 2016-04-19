<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/14/2016
 * Time: 4:54 PM
 */

namespace PHPixme;

/**
 * Class Pot
 * @package PHPixme
 * @decription
 * Pot is an exception class that may contain any data you wish, designed to pass more than just a message.
 * Please note there is no EmptyPot. It must always have a value.
 */
class Pot extends \Exception implements SingleCollectionInterface, \Countable
{
  use SingleIteratorTrait;
  protected $contents;

  public function __construct($data, $message = '')
  {
    $this->message = $message;
    $this->contents = $data;
  }

  public function __invoke()
  {
    return $this->contents;
  }

  public function get()
  {
    return $this->contents;
  }

  public static function assertPotType($pot)
  {
    if ($pot instanceof self) {
      return $pot;
    }
    throw new \UnexpectedValueException('Expected value was not an instance of Pot');
  }

  /**
   * @param mixed $head
   * @param array $items
   * @return Pot
   */
  static public function of($head, ...$items)
  {
    return new self($head, '');
  }

  /**
   * Transfer the data from one container to another
   * @param \Traversable|array $traversable
   * @return static - A new instance of the class
   * @throws \LengthException - When the input is an empty collection
   */
  public static function from($traversable)
  {
    __PRIVATE__::assertTraversable($traversable);
    foreach ($traversable as $value) {
      return new static($value, '');
    }
    throw new \LengthException('Traversable|array must not be empty!');
  }

  /**
   * Map across the container
   * @param callable $hof ($value, $key, $container): mixed
   * @return Pot
   */
  public function map(callable $hof)
  {
    return static::of(call_user_func($hof, $this->contents, 0, $this));
  }

  /**
   * Fold across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   */
  public function fold(callable $hof, $startVal)
  {
    return call_user_func($hof, $startVal, $this->contents, 0, $this);
  }

  /**
   * Fold Right across this class
   * @param callable $hof ($prevVal, $value, $key, $container): mixed
   * @param mixed $startVal
   * @return mixed - whatever the last cycle of $hof returns
   */
  public function foldRight(callable $hof, $startVal)
  {
    return call_user_func($hof, $startVal, $this->contents, 0, $this);
  }

  /**
   * @param callable $hof ($value, $key, $container):static
   * @return Pot
   * @throws \Exception - if the data type returned by callback wasn't its kind
   */
  public function flatMap(callable $hof)
  {
    return static::assertPotType(call_user_func($hof, $this->contents, 0, $this));
  }

  /**
   * @return self
   * @throws \UnexpectedValueException if the data-set could not be flattened
   */
  public function flatten()
  {
    return static::assertPotType($this->contents);
  }

  /**
   * Checks to see if the $predicate applies true to all within a container
   * @param callable $predicate ($value, $key, $container): boolean
   * @return boolean
   */
  public function forAll(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->contents, 0, $this);
  }

  /**
   * Checks to see if the $predicate applies true to none within a container
   * @param callable $predicate ($value, $key, $container): boolean
   * @return boolean
   */
  public function forNone(callable $predicate)
  {
    return !((boolean)call_user_func($predicate, $this->contents, 0, $this));
  }

  /**
   * Checks to see if the $predicate applies true to at least one within a container
   * @param callable $predicate ($value, key, $container): boolean
   * @return boolean
   */
  public function forSome(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->contents, 0, $this);
  }

  /**
   * Preform $hof over the container
   * @param callable $hof ($value, $key, $container) : null
   * @return $this
   */
  public function walk(callable $hof)
  {
    call_user_func($hof, $this->contents, 0, $this);
    return $this;
  }

  /**
   * Converts the container to an array, in any structure that is appropriate within that array
   * @return array
   */
  public function toArray()
  {
    return [$this->contents];
  }

  /**
   * Is the container empty?
   * @return bool
   */
  public function isEmpty()
  {
    return false;
  }

  /**
   * Search the container
   * @param callable $hof ($value, $key, $container):boolean
   * @return \PHPixme\Some|\PHPixme\None
   */
  public function find(callable $hof)
  {
    return call_user_func($hof, $this->contents, 0, $this) ? Some($this->contents) : None();
  }

  // -- Iterator Interface --
  /**
   * @inheritdoc
   */
  public function current()
  {
    return $this->done ? null : $this->contents;
  }
  // == Iterator Interface ==
  // -- Count Interface --
  /**
   * @inheritdoc
   */
  public function count()
  {
    return 1;
  }
  // == Count Interface ==
}