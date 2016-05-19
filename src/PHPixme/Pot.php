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
 * Pot is a single collection who is designed to be thrown. It is not closed like other collections, mirroring the ability of \stdClass in that anything may be attached to the object outside of what is predefined. 
 * @package PHPixme
 */
class Pot extends \Exception implements
  CollectionInterface
  , UnaryApplicativeInterface
  , \Countable
{
  use AssertTypeTrait, ImmutableConstructorTrait;
  protected $contents;

  /**
   * Pot constructor.
   * @param mixed $contents
   * @param string $message
   * @param int $code
   * @param \Exception $previous
   * @inheritdoc
   */
  public function __construct($contents, $message = "", $code = 0, \Exception $previous = null)
  {
    $this->assertOnce();
    parent::__construct($message, $code, $previous);
    $this->contents = $contents;
  }

  /**
   * Take some data and pot it using a previous Exception
   * @param \Exception $exception
   * @param mixed $data=
   * @return Pot
   */
  public static function fromThrowable(\Exception $exception, $data = null) {
    return new static($data, $exception->getMessage(), $exception->getCode(), $exception);
  }

  /**
   * get the contents of the pot
   * @return mixed
   */
  public function __invoke()
  {
    return $this->contents;
  }

  /**
   * get the contents of the pot
   * @return mixed
   */
  public function get()
  {
    return $this->contents;
  }

  /**
   * @param mixed $head
   * @return Pot
   */
  static public function of($head)
  {
    return new self($head, '');
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
    return Pot::assertType(call_user_func($hof, $this->contents, 0, $this));
  }

  /**
   * @return self
   * @throws \UnexpectedValueException if the data-set could not be flattened
   */
  public function flatten()
  {
    return Pot::assertType($this->contents);
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

  
  // -- Count Interface --
  /**
   * @inheritdoc
   */
  public function count()
  {
    return 1;
  }
  // == Count Interface ==

  /**
   * @inheritdoc
   */
  public function getIterator()
  {
    return new \ArrayIterator([$this->contents]);
  }
}