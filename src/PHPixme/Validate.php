<?php
namespace PHPixme;

/**
 * Class Validate
 * @package PHPixme
 */
abstract class Validate implements
  BiasedDisjunctionInterface
  , UnaryApplicativeRightDisjunctionInterface
  , \Countable
{
  use ImmutableConstructorTrait
    , ClosedTrait
    , RootTypeTrait;

  const shortName = 0;
  protected $value;
  /**
   * @inheritdoc
   */
  public static function ofRight($item)
  {
    return new Valid($item);
  }
}