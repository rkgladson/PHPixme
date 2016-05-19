<?php

namespace PHPixme;

/**
 * Class Preferred
 * Preferred is the successful right hand side of a right handed biased track of a Exclusive
 * @package PHPixme
 */
class Preferred extends Exclusive implements
  RightHandSideType
{
  use RightHandedTrait
    , ImmutableConstructorTrait;
  private $value;
  const shortName = 'preferred';

  /**
   * Preferred constructor.
   * @param mixed $value the value which will be held
   */
  public function __construct($value)
  {
    $this->assertOnce();
    $this->value = $value;
  }

  /**
   * @inheritdoc
   * @return Preferred
   */
  public static function of($value)
  {
    return new static($value);
  }

  /**
   * @inheritdoc
   * @return mixed
   */
  public function merge()
  {
    return $this->value;
  }

  /**
   * @inheritdoc
   * @return Undesired;
   */
  public function swap()
  {
    return $this::ofLeft($this->value);
  }

  /** @inheritdoc */
  public function count()
  {
    return 1;
  }

  /** @inheritdoc */
  public function isEmpty()
  {
    return false;
  }

  /**
   * @inheritdoc
   * @return Right
   */
  public function toUnbiasedDisjunctionInterface()
  {
    return new Right($this->value);
  }

  /**
   * @inheritdoc
   * @return array
   */
  public function toArray()
  {
    return ['preferred' => $this->value];
  }

  /**
   * @inheritdoc
   * @return Preferred
   */
  public function map(callable $hof)
  {
    return new static($hof($this->value, 0, $this));
  }


  /**
   * @inheritdoc
   * @return Exclusive
   */
  public function flatMap(callable $hof)
  {
    return Exclusive::assertType($hof($this->value, 0, $this));
  }

  /**
   * @inheritdoc
   * @return Exclusive
   */
  public function flatten()
  {
    return Exclusive::assertType($this->value);
  }

  /**
   * @inheritdoc
   */
  public function fold(callable $hof, $startVal)
  {
    return $hof($startVal, $this->value, 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function foldRight(callable $hof, $startVal)
  {
    return $hof($startVal, $this->value, 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function find(callable $hof)
  {
    return $hof($this->value, 0, $this) ? new Some($this->value) : None::getInstance();
  }

  /**
   * @inheritdoc
   */
  public function forAll(callable $predicate)
  {
    return (boolean) $predicate($this->value, 0, $this);
  }

  /**
   * @inheritdoc
   */
  public function forNone(callable $predicate)
  {
   return !($predicate($this->value, 0, $this));
  }

  /**
   * @inheritdoc
   */
  public function forSome(callable $predicate)
  {
    return (boolean) $predicate($this->value, 0, $this);
  }

  /**
   * Returns an identity on Right hand sides
   * @return $this
   */
  public function flattenLeft()
  {
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function walk(callable $hof)
  {
    $hof($this->value, 0, $this);
    return $this;
  }

}