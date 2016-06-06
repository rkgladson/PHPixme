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
  use NothingCollectionTrait;
  /**
   * @var self
   */
  protected static $instance = null;

  /**
   * @inheritdoc
   */
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
  public static function of($head = null)
  {
    return static::getInstance();
  }


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
   * @throws \LengthException
   */
  public function reduce(callable $hof)
  {
    throw new \LengthException('Cannot reduce on None. Behaviour is undefined');
  }

  /**
   * @inheritdoc
   * @throws \LengthException
   */
  public function reduceRight(callable $hof)
  {
    throw new \LengthException('Cannot reduceRight on None. Behaviour is undefined');
  }

  /**
   * @inheritdoc
   */
  public function toArray()
  {
    return [];
  }


  // -- Countable Interface --
  /**
   * @inheritdoc
   */
  public function count()
  {
    return 0;
  }

  // == Countable Interface ==
  /**
   * @inheritdoc
   */
  public function getIterator()
  {
    return new \EmptyIterator();
  }
}