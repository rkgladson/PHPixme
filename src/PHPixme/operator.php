<?php
namespace PHPixme;
// -- + --
/**
 * add, short for addition, a stand in for +
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function add($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[add], func_get_args());
}
const add = __NAMESPACE__ . '\add';
__PRIVATE__::$instance[add] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs + $rhs;
});
// == + ==

// -- - --
/**
 * sub, short for subtract, a stand in for -
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function sub($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[sub], func_get_args());
}
const sub = __NAMESPACE__ . '\sub';
__PRIVATE__::$instance[sub] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs - $rhs;
});
// == - ==

// -- * --
/**
 * mul, short for multiply, a stand in for *
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function mul($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[mul], func_get_args());
}
const mul = __NAMESPACE__ . '\mul';
__PRIVATE__::$instance[mul] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs * $rhs;
});
// == * ==

// -- / --
/**
 * div, short for divide, a stand in for /
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function div($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[div], func_get_args());
}
const div = __NAMESPACE__ . '\div';
__PRIVATE__::$instance[div] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs / $rhs;
});
// == / ==

// -- % --
/**
 * mod, short for modulous, a stand in for %
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function mod($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[mod], func_get_args());
}
const mod = __NAMESPACE__ . '\mod';
__PRIVATE__::$instance[mod] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs % $rhs;
});
// == % ==

// -- ** --
/**
 * pow, short for power. A stand in for **
 * @sig (int|float $lhs) -> (int|float $rhs) -> int|float $z
 * @param int|float $lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function pow($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pow], func_get_args());
}
const pow = __NAMESPACE__ . '\pow';
__PRIVATE__::$instance[pow] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs ** $rhs;
});
// == ** ==

// -- ''. --
/**
 * cat, short for string concatenation, a stand in for .
 * @param string $lhs
 * @param string $rhs
 * @return string|\Closure
 */
function cat($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[cat], func_get_args());
}
const cat = __NAMESPACE__ . '\cat';
__PRIVATE__::$instance[cat] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs . $rhs;
});
// == ''. ==

// -- []+ --
/**
 * app, short for application or array concatenation, a stand in for + on arrays
 * @sig (array $lhs) -> (array $rhs) -> array $z
 * @param array $lhs
 * @param array $rhs
 * @return array|\Closure
 */
function app($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[add], func_get_args());
}
const app = __NAMESPACE__ . '\app';
// == []+ ==


// -- -# --
/**
 * neg short for negation, a stand in for -#
 * @sig (int|float $rhs)-> -$lhs
 * @param int|float $rhs
 * @return int|float|\Closure
 */
function neg($rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[neg], func_get_args());
}
const neg = __NAMESPACE__ . '\neg';
__PRIVATE__::$instance[neg] = __PRIVATE__::curryExactly1(function ($rhs) {
  return -$rhs;
});
// == -# ==


// -- == --
/**
 * eq, short for equals, a stand in for ==
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function eq($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[eq], func_get_args());
}
const eq = __NAMESPACE__ . '\eq';
__PRIVATE__::$instance[eq] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs == $rhs;
});
// == == ==

// -- === --
/**
 * id, short for identity, or strictly equals, a stand in for ===
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function id($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[id], func_get_args());
}
const id = __NAMESPACE__ . '\id';
__PRIVATE__::$instance[id] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs === $rhs;
});
// == === ==

// -- != --
/**
 * neq, short for not equals, a stand in for !=
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function neq($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[neq], func_get_args());
}
const neq = __NAMESPACE__ . '\neq';
__PRIVATE__::$instance[neq] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs != $rhs;
});
// == != ==

// -- !== --
/**
 * nid, short for not the identity, or not strictly equals, a stand in for !==
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function nid($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[nid], func_get_args());
}
const nid = __NAMESPACE__ . '\nid';
__PRIVATE__::$instance[nid] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs !== $rhs;
});
// == !== ==

// -- > --
/**
 * gt, short for greater than, a stand in for >
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function gt($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[gt], func_get_args());
}
const gt = __NAMESPACE__ . '\gt';
__PRIVATE__::$instance[gt] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs > $rhs;
});
// == > ==

// -- >= --
/**
 * gte, short for greater than or equal to, a stand in for >=
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function gte($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[gte], func_get_args());
}
const gte = __NAMESPACE__ . '\gte';
__PRIVATE__::$instance[gte] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs > $rhs;
});
// == >= ==

// -- < --
/**
 * lt, short for greater than, a stand in for >
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function lt($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lt], func_get_args());
}
const lt = __NAMESPACE__ . '\lt';
__PRIVATE__::$instance[lt] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs < $rhs;
});
// == < ==

// -- <= --
/**
 * lte, short for greater than or equal to, a stand in for >=
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function lte($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lte], func_get_args());
}
const lte = __NAMESPACE__ . '\lte';
__PRIVATE__::$instance[lte] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs <= $rhs;
});
// == <= ==

// -- <=> --
/**
 * ufo, short for the space ship operator, or the three way comparison, a stand in for <=>
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return int|\Closure
 */
function ufo($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[ufo], func_get_args());
}
const ufo = __NAMESPACE__ . '\ufo';
__PRIVATE__::$instance[ufo] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs != $rhs ? $lhs > $rhs ? 1 : -1 : 0;
});
// == <=> ==

// -- && --
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
// == && ==

// -- || --
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
// == || ==

// -- xor --
/**
 * xorL, short for logical xor, a stand in for xor keyword
 * @sig ($lhs)->($rhs)-> boolean $z
 * @param mixed $lhs
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function xorL($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[xorL], func_get_args());
}
const xorL = __NAMESPACE__ . '\xorL';
__PRIVATE__::$instance[xorL] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs xor $rhs;
});
// == xor ==

// -- ! --
/**
 * not, or invert, stand in for !
 * @sig ($rhs) -> boolean $z
 * @param mixed $rhs
 * @return boolean|\Closure
 */
function notL($rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[notL], func_get_args());
}
const notL = __NAMESPACE__ . '\notL';
__PRIVATE__::$instance[notL] = __PRIVATE__::curryExactly1(function ($rhs) {
  return !$rhs;
});
// == ! ==


// -- & --
/**
 * andB, short for bitwise and, a stand in for &
 * @sig (int $lhs)->(int $rhs)-> int $z
 * @param int $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function andB($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[andB], func_get_args());
}
const andB = __NAMESPACE__ . '\andB';
__PRIVATE__::$instance[andB] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs & $rhs;
});
// == & ==

// -- | --
/**
 * orB, short for logical or, a stand in for |
 * @sig (int $lhs)->(int $rhs)-> int $z
 * @param int $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function orB($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[orB], func_get_args());
}
const orB = __NAMESPACE__ . '\orB';
__PRIVATE__::$instance[orB] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs | $rhs;
});
// == | ==

// -- ^ --
/**
 * xorL, short for logical xor, a stand in for xor keyword
 * @sig (int $lhs)->(int $rhs)-> int $z
 * @param int $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function xorB($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[xorB], func_get_args());
}
const xorB = __NAMESPACE__ . '\xorB';
__PRIVATE__::$instance[xorB] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs ^ $rhs;
});
// == ^ ==

// -- ~ --
/**
 * notB, not binary, invert, or 2's compliment, a stand in for ~
 * @sig (int $rhs) -> int $z
 * @param int $rhs
 * @return int|\Closure
 */
function notB($rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[notB], func_get_args());
}
const notB = __NAMESPACE__ . '\notB';
__PRIVATE__::$instance[notB] = __PRIVATE__::curryExactly1(function ($rhs) {
  return ~$rhs;
});
// == ~ ==

// -- << --
/**
 * shiftL, or shift left, a stand in for <<
 * @param int|float $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function shiftL($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[shiftL], func_get_args());
}
const shiftL = __NAMESPACE__ . '\shiftL';
__PRIVATE__::$instance[shiftL] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs << $rhs;
});
// == << ==

// -- >> --
/**
 * shiftR, or shift left, a stand in for >>
 * @param int|float $lhs
 * @param int $rhs
 * @return int|\Closure
 */
function shiftR($lhs = null, $rhs = null)
{
  return call_user_func_array(__PRIVATE__::$instance[shiftR], func_get_args());
}
const shiftR = __NAMESPACE__ . '\shiftR';
__PRIVATE__::$instance[shiftR] = __PRIVATE__::curryExactly2(function ($lhs, $rhs) {
  return $lhs >> $rhs;
});
// == >> ==

