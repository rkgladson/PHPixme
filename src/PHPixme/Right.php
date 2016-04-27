<?php
namespace PHPixme;


class Right extends Either
{

  private $value;
  public  function __construct ($value) {
    $this->value = $value;
  }
  /**
   * @inheritdoc
   */
  public static function of($value) {
    return new static($value);
  }

  /**
   * @inheritdoc
   * @return None
   */
  public function left() {
    return None::getInstance();
  }
  /**
   * @inheritdoc
   */
  public function isLeft()
  {
    return false;
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
  public function right() {
    return new Some($this->value);
  }
  /**
   * @inheritdoc
   */
  public function isRight()
  {
    return true;
  }

  public function flattenRight()
  {
    return Either::assertIs($this->value);
  }

  /**
   * @inheritdoc
   * @sig ((x->a), (x->b)) -> b
   */
  public function fold(callable $leftFn, callable $rightFn)
  {
    return call_user_func($rightFn, $this->value);
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