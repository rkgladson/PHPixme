<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:21 AM
 */
namespace PHPixme;
/**
 * Class None
 * @package PHPixme
 * This class is a singleton! Please us it's Function companion for eas of use!
 */
class None extends Maybe
{
  /**
   * @var self
   */
  protected static $instance = null;

  public function contains($x)
  {
    return false;
  }

  /**
   * @inheritdoc
   */
  public function exists(callable $hof)
  {
    return false;
  }


  /**
   * @inheritdoc
   */
  public function get()
  {
    throw new \Exception('Cannot get on None!');
  }

  /**
   * @inheritdoc
   */
  public static function getInstance()
  {
    // @codeCoverageIgnoreStart
    // This section will never execute under testing as it passes it earlier on.
    // It is however being tested for, as the output should be a constant instance of itself.
    if (is_null(static::$instance)) {
      static::$instance = new static();
    }
    // @codeCoverageIgnoreEnd

    return static::$instance;
  }

  // -- Natural Transformation Static --
  /**
   * @inheritdoc
   */
  public static function of(...$args)
  {
    return static::getInstance();
  }

  /**
   * @inheritdoc
   */
  public static function from($args)
  {
    return static::getInstance();
  }
  // == Natural transformation Static ==

  // -- Magic Methods --
  /**
   * @codeCoverageIgnore
   */
  protected function __clone()
  {
    // This space intentionally left blank
  }

  /**
   * @codeCoverageIgnore
   */
  protected function __wakeup()
  {
    // This space intentionally left blank
  }

  /**
   * None constructor.
   * @codeCoverageIgnore
   */
  protected function __construct()
  {
    // This space intentionally left blank
  }

  // == Magic Methods ==

  // -- Natural Transformation interface methods --
  /**
   * @inheritdoc
   */
  public function isEmpty()
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function find(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function flatten()
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function flatMap(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function filter(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function filterNot(callable $hof)
  {
    return $this;
  }


  /**
   * @inheritdoc
   */
  public function fold(callable $hof, $startVal)
  {
    return $startVal;
  }

  /**
   * @inheritdoc
   */
  public function forAll(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function forNone(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   */
  public function forSome(callable $predicate)
  {
    return false;
  }


  /**
   * @inheritdoc
   */
  public function map(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function reduce(callable $hof)
  {
    throw new \InvalidArgumentException('Cannot reduce on None. Behaviour is undefined');
  }

  /**
   * @inheritdoc
   */
  public function toArray()
  {
    return [];
  }

  /**
   * @inheritdoc
   */
  public function walk(callable $hof)
  {
    return $this;
  }

  // == Natural Transformation interface methods ==

  // -- Iterator interface methods--
  /**
   * @return null
   * @codeCoverageIgnore - Transversable interface will never run this
   */
  public function key()
  {
    return null;
  }

  /**
   * None is always at it's end
   * @return false
   */
  public function valid()
  {
    return false;
  }

  /**
   * @codeCoverageIgnore Transversable interface will never exectue this
   */
  public function next()
  {
    // This space is intentionally left blank
  }

  public function rewind()
  {
    // This space is intentionally left blank
  }

  /**
   * @codeCoverageIgnore Interface will never execute this
   * @return null
   */
  public function current()
  {
    return null;
  }
  // == Iterator interface methods==

  // -- Countable Interface --
  public function count()
  {
    return 0;
  }
  // == Countable Interface ==
}