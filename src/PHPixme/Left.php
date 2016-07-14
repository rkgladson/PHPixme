<?php
namespace PHPixme;

/**
 * Class Left
 * The left hand side of the Either disjunction
 * @package PHPixme
 */
class Left extends Either implements LeftHandSideType
{
  use LeftHandedTrait
    , ImmutableConstructorTrait;
  private $value;

  /**
   * Left constructor.
   * @param mixed $value the value for the left hand track to contain
   */
  public function __construct($value)
  {
    $this->assertOnce();
    $this->value = $value;
  }

  /**
   * @inheritdoc
   * @return self
   */
  public static function of($value)
  {
    return new static($value);
  }

  /**
   * @inheritdoc
   * @return Some
   */
  public function left()
  {
    return new Some($this->value);
  }

  /**
   * @inheritdoc
   * @return Either
   */
  public function flattenLeft()
  {
    return __CONTRACT__::contentIsA(static::rootType(), $this->value);
  }

  /**
   * @inheritdoc
   * @return None
   */
  public function right()
  {
    return None::getInstance();
  }

  /**
   * @inheritdoc
   * return Left
   */
  public function flattenRight()
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function vFold(callable $lhs, callable $rhs)
  {
    return $lhs($this->value);
  }

  /**
   * @inheritdoc
   */
  public function vMap(callable $lhs, callable $rhs)
  {
    return new static($lhs($this->value));
  }

  /**
   * @inheritdoc
   * @return Right
   */
  public function swap()
  {
    return new Right($this->value);
  }

  /**
   * @inheritdoc
   */
  public function merge()
  {
    return $this->value;
  }

}