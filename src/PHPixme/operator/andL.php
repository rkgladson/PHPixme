<?php
namespace PHPixme;
/**
 * andL, short for logical and, a stand in for &&
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function andL($lhs = null, $rhs = null)
{
  // Unwrapped curry to have a smaller stack.
  $_ = __PRIVATE__::placeholder();
  $arity = func_num_args();
  $lhsX = $arity > 0 && $lhs !== $_;
  $rhsX = $arity > 1 && $rhs !== $_;
  if ($lhsX && $rhsX) { // Both defined
    // Return the result
    return $lhs && $rhs;
  }
  if ($lhsX) { // first is defined
    if (!$lhs) { // if lhs is false, then the result is always false
      $alwaysFalse = function ($rhs = null)  use (&$alwaysFalse, $_){
        return func_num_args() !== 0 && $rhs !== $_ ? false: $alwaysFalse;
      };
      return $alwaysFalse;
    }
    // Otherwise the value is determined only by the rhs
    $rightDeterminate = function ($rhs = null) use ($_, &$rightDeterminate){
      return func_num_args() !== 0 && $rhs !== $_ ? ((boolean) $rhs): $rightDeterminate;
    };
    return $rightDeterminate;
  }
  if ($rhsX) { // Is the right hand side defined?
    $indeterminate = function ($lhs = null) use ($_, &$indeterminate, $rhs) {
      return func_num_args() !== 0 && $lhs !== $_ ? ($lhs && $rhs): $indeterminate;
    };
    return $indeterminate;
  }

  // both are undefined, send a wrapped version of itself. (rare)
  return __PRIVATE__::$instance[andL];

}
const andL = __NAMESPACE__ . '\andL';
__PRIVATE__::$instance[andL] = toClosure(andL);
