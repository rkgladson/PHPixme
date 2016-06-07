<?php
namespace PHPixme;

/**
 * Interface DisjunctiveMapInterface
 * @package PHPixme
 * A disjunctive type with a two handed map.
 */
interface DisjunctiveMapInterface
{
  /**
   * Map over tha value contained in the disjunction, based on the Disjunctive Side.
   * Unlike Functor's Map, the container and offset are not given, as Disjunctions are not a collection,
   * but a structure.
   * @param callable $lhs used by LeftHandedSideType (x->a)
   * @param callable $rhs used by RightHandedSideType (x->b)
   * @return LeftHandSideType|RightHandSideType
   * @sig (((mixed x)->a), ((mixed x)->b)) -> a or b
   */
  public function vMap(callable $lhs, callable $rhs);
}