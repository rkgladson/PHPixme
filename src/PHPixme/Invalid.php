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
  use LeftHandedTrait, NothingCollectionTrait;
  const shortName = 'invalid';
  
  /**
   * @var array
   */
  private $accumulate = [];

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
  public function getIterator()
  {
    return new \EmptyIterator();
  }
  
  /** @inheritdoc */
  public function toUnbiasedDisjunctionInterface()
  {
    return Either::ofLeft($this->accumulate);
  }

}