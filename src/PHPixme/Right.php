<?php
namespace PHPixme;

/**
 * Class Right
 * The right hand side of the unbiased disjunction Either
 * @package PHPixme
 */
class Right extends Either implements RightHandSideType
{
  use RightHandedTrait
    , ImmutableConstructorTrait;
  private $value;

  /**
   * Right constructor.
   * @param mixed $value the value contained by the right hand side
   */
  public function __construct($value)
  {
    $this->assertOnce();
    $this->value = $value;
  }

  /**
   * @inheritdoc
   */
  public static function of($value)
  {
    return new static($value);
  }

  /**
   * @inheritdoc
   * @return None
   */
  public function left()
  {
    return None::getInstance();
  }


  /**
   * @inheritdoc
   */
  public function flattenLeft()
  {
    return $this;
  }

  /**
   * @inheritdoc
   * @return Some
   */
  public function right()
  {
    return new Some($this->value);
  }

  /**
   * @inheritdoc
   * @return Either
   */
  public function flattenRight()
  {
    return static::assertRootType($this->value);
  }

  /**
   * @inheritdoc
   * @sig ((x->a), (x->b)) -> b
   */
  public function vFold(callable $lhs, callable $rhs)
  {
    return $rhs($this->value);
  }

  public function vMap(callable $lhs, callable $rhs)
  {
    return new static($rhs($this->value));
  }

  /**
   * @inheritdoc
   * @return Left
   */
  public function swap()
  {
    return new Left($this->value);
  }

  /**
   * @inheritdoc
   */
  public function merge()
  {
    return $this->value;
  }
}