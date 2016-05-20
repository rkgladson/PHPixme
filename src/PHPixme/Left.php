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
   * @return static
   */
  public static function of($value) {
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
    return Either::assertType($this->value);
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
   * @sig ((x->a), (x->b)) -> a
   */
  public function fold(callable $leftFn, callable $rightFn)
  {
    return $leftFn($this->value, $this);
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