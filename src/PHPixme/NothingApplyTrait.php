<?php
namespace PHPixme;

/**
 * Class NothingApplyTrait
 * @package PHPixme
 * A boilerplate for implementors of {@see ApplyInterface} that are a perceived nothing
 */
trait NothingApplyTrait
{
  /**
   * Nothing should return itself, as a 'Nothing' is not a run-time error, but a stand in
   * for a no operation step.
   * @param FunctorInterface $functor
   * @return $this
   */
  final public function apply(FunctorInterface $functor) {
    return $this;
  }
}