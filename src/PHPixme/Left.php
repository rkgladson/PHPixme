<?php
namespace PHPixme;

class Left extends Either
{
  private $value;

  public function __construct($value)
  {
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
   */
  public function isLeft()
  {
    return true;
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
   */
  public function isRight()
  {
    return false;
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
    return call_user_func($leftFn, $this->value);
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