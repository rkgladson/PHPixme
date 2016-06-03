<?php
namespace PHPixme;

/**
 * Interface ApplyInterface
 * @package PHPixme
 */
interface ApplyInterface
{
  /**
   * Applies the contents of itself across the map function of the $mappable
   * Note: Contents should be a callable if it is not a 'Nothing' Container
   * @param FunctorInterface $functor
   * @return FunctorInterface
   * @throws \PHPixme\exception\InvalidContentException
   */
  public function apply(FunctorInterface $functor);
}