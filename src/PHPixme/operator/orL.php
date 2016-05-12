<?php
namespace PHPixme;
/**
 * orL, short for logical or, a stand in for ||
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function orL($lhs = null, $rhs = null)
{
  // Unwrapped curry to have a smaller stack.
  $_ = __PRIVATE__::placeholder();
  $arity = func_num_args();
  $lhsX = $arity > 0 && $lhs !== $_;
  $rhsX = $arity > 1 && $rhs !== $_;
  if ($lhsX && $rhsX) { // Both defined
    // Return the result
    return $lhs || $rhs;
  }
  if ($lhsX) {
    if ($lhs) {
      $alwaysTrue = function ($rhs = null) use (&$alwaysTrue, $_){
        return func_num_args() !== 0 && $rhs !== $_ ? true : $alwaysTrue;
      };
      return $alwaysTrue;
    }
    $rightDeterminate = function ($rhs = null) use (&$rightDeterminate, $_) {
      return func_num_args() !== 0 && $rhs !== $_ ? ((boolean) $rhs): $rightDeterminate;
    };
    return $rightDeterminate;
  }

  if ($rhsX) { // Is the right hand side defined?
    $indeterminate = function ($lhs = null) use ($_, &$indeterminate, $rhs) {
      return func_num_args() !== 0 && $lhs !== $_ ? ($lhs || $rhs): $indeterminate;
    };
    return $indeterminate;
  }

  // both are undefined, send a wrapped version of itself. (rare)
  return __PRIVATE__::$instance[orL];
}
const orL = __NAMESPACE__ . '\orL';
__PRIVATE__::$instance[orL] = toClosure(orL);