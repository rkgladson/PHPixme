<?php
namespace PHPixme;

/**
 * Class Validate
 * @package PHPixme
 */
abstract class Validate implements
  BiasedDisjunctionInterface
  , FunctorInterface
  , ApplyInterface
  , FoldableInterface
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

  /** @noinspection PhpDocSignatureInspection
   * While the type hint (from the interface)
   * says that it can use any functor interface, please note that
   * this will throw an error if the value passed to it is not its own type.
   * @param Validate $functor
   * @return Validate
   */
  abstract public function apply(FunctorInterface $functor);
}