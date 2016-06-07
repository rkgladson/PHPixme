<?php
namespace PHPixme;
/**
 * Class Undesired
 * Represents the failing left hand side of a right handed biased track of a Exclusive
 * @package PHPixme
 */
class Undesired extends Exclusive implements
  LeftHandSideType
{
  use LeftHandedTrait
    , ImmutableConstructorTrait
    , NothingCollectionTrait;
  private $value;
  const shortName = 'undesired';

  /**
   * Undesired constructor.
   * @param mixed $value the value of which will be held
   */
  public function __construct($value)
  {
    $this->assertOnce();
    $this->value = $value;
  }

  /**
   * @inheritdoc
   * @return Undesired
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
   * @return Preferred;
   */
  public function swap()
  {
    return $this::ofRight($this->value);
  }

  /** @inheritdoc */
  public function count()
  {
    return 0;
  }

  /**
   * @inheritdoc
   * @return Left
   */
  public function toUnbiasedDisjunction()
  {
    return new Left($this->value);
  }

  /**
   * @inheritdoc
   */
  public function toArray()
  {
    return [ $this::shortName => $this->value];
  }

  /**
   * @inheritdoc
   * @return Exclusive
   */
  public function flattenLeft()
  {
    return Exclusive::assertType($this->value);
  }
}