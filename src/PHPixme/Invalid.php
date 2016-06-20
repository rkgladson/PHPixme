<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/6/2016
 * Time: 3:05 PM
 */

namespace PHPixme;

/**
 * Class Invalid
 * @package PHPixme
 */
class Invalid extends Validate implements
  LeftHandSideType
{
  use LeftHandedTrait, ImmutableConstructorTrait, NothingFunctorTrait, NothingFoldTrait;
  const shortName = 'invalid';
  
  /**
   * @var array
   */
  private $accumulate = [];

  /**
   * Invalid constructor.
   * @param array|FoldableInterface $problemList
   */
  public function __construct($problemList)
  {
    $this->assertOnce();
    __CONTRACT__::isNonEmpty($problemList);
    $this->accumulate = __PRIVATE__::traversableToArray($problemList);
  }

  /** @inheritdoc */
  public function count()
  {
    return 0;
  }

  /**
   * @inheritdoc
   * @return array
   */
  public function merge()
  {
    return $this->accumulate;
  }
  
  /** @inheritdoc */
  public function toArray()
  {
    return [static::shortName => $this->accumulate];
  }
  
  /** @inheritdoc */
  public function toUnbiasedDisjunction()
  {
    return Either::ofLeft($this->accumulate);
  }

  /**
   * @inheritdoc
   * @param Validate $functor
   */
  public function apply(FunctorInterface $functor)
  {
    $this::assertRootType($functor);
    return $functor->isLeft() ?  new static ($this->accumulate + $functor->merge()) : $this;
  }
}