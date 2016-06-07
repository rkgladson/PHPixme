<?php
namespace PHPixme;

/**
 * Interface DisjunctiveFoldInterface
 * @package PHPixme
 * As disjunctive type with a two handed fold
 */
interface DisjunctiveFoldInterface
{
  /**
   * Fold over the value contained by the Disjunction, base on the Disjunctive side.
   * Unlike the Foldable, no start value can be provided, and must ba handled by the callback, 
   * And this is due to the inherit structural nature of Disjunctions, where the state is held by the handedness
   * which is independent of the contained value.
   * @param callable $lhs ((mixed value)->a) used by LeftHandedSideType
   * @param callable $rhs ((mixed value)->b) used by used by RightHandedSideType
   * @return mixed
   * @sig (((mixed value)->a), ((mixed value)->b)) -> a or b
   */
  public function vFold(callable $lhs, callable $rhs);
}