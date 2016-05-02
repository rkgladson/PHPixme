<?php
namespace PHPixme;
// -- + --
/**
 * add, short for addition, a stand in for +
 * @sig (int|float $x) -> (int|float $y) -> int|float $z
 * @param int|float $x
 * @param int|float $y
 * @return int|float|\Closure
 */
function add($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[add], func_get_args());
}
const add = __NAMESPACE__ . '\add';
__PRIVATE__::$instance[add] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x + $y;
});
// == + ==

// -- - --
/**
 * sub, short for subtract, a stand in for -
 * @sig (int|float $x) -> (int|float $y) -> int|float $z
 * @param int|float $x
 * @param int|float $y
 * @return int|float|\Closure
 */
function sub($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[sub], func_get_args());
}
const sub = __NAMESPACE__ . '\sub';
__PRIVATE__::$instance[sub] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x - $y;
});
// == - ==

// -- * --
/**
 * mul, short for multiply, a stand in for *
 * @sig (int|float $x) -> (int|float $y) -> int|float $z
 * @param int|float $x
 * @param int|float $y
 * @return int|float|\Closure
 */
function mul($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[mul], func_get_args());
}
const mul = __NAMESPACE__ . '\mul';
__PRIVATE__::$instance[mul] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x * $y;
});
// == * ==

// -- / --
/**
 * div, short for divide, a stand in for /
 * @sig (int|float $x) -> (int|float $y) -> int|float $z
 * @param int|float $x
 * @param int|float $y
 * @return int|float|\Closure
 */
function div($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[div], func_get_args());
}
const div = __NAMESPACE__ . '\div';
__PRIVATE__::$instance[div] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x / $y;
});
// == / ==

// -- % --
/**
 * mod, short for modulous, a stand in for %
 * @sig (int|float $x) -> (int|float $y) -> int|float $z
 * @param int|float $x
 * @param int|float $y
 * @return int|float|\Closure
 */
function mod($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[mod], func_get_args());
}
const mod = __NAMESPACE__ . '\mod';
__PRIVATE__::$instance[mod] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x % $y;
});
// == % ==

// -- ** --
/**
 * pow, short for power. A stand in for **
 * @sig (int|float $x) -> (int|float $y) -> int|float $z
 * @param int|float $x
 * @param int|float $y
 * @return int|float|\Closure
 */
function pow($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[pow], func_get_args());
}
const pow = __NAMESPACE__ . '\pow';
__PRIVATE__::$instance[pow] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x ** $y;
});
// == ** ==

// -- ''. --
/**
 * cat, short for string concatenation, a stand in for .
 * @param string $x
 * @param string $y
 * @return string|\Closure
 */
function cat($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[cat], func_get_args());
}
const cat = __NAMESPACE__ . '\cat';
__PRIVATE__::$instance[cat] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x . $y;
});
// == ''. ==

// -- []+ --
/**
 * app, short for application or array concatenation, a stand in for + on arrays
 * @sig (array $x) -> (array $y) -> array $z
 * @param array $x
 * @param array $y
 * @return array|\Closure
 */
function app($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[add], func_get_args());
}
const app = __NAMESPACE__ . '\app';
// == []+ ==


// -- -# --
/**
 * neg short for negation, a stand in for -#
 * @sig (int|float $x)-> -$x
 * @param int|float $x
 * @return int|float|\Closure
 */
function neg($x = null)
{
  return call_user_func_array(__PRIVATE__::$instance[neg], func_get_args());
}
const neg = __NAMESPACE__ . '\neg';
__PRIVATE__::$instance[neg] = __PRIVATE__::curryExactly1(function ($x) {
  return -$x;
});
// == -# ==


// -- == --
/**
 * eq, short for equals, a stand in for ==
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function eq($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[eq], func_get_args());
}
const eq = __NAMESPACE__ . '\eq';
__PRIVATE__::$instance[eq] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x == $y;
});
// == == ==

// -- === --
/**
 * id, short for identity, or strictly equals, a stand in for ===
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function id($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[id], func_get_args());
}
const id = __NAMESPACE__ . '\id';
__PRIVATE__::$instance[id] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x === $y;
});
// == === ==

// -- != --
/**
 * neq, short for not equals, a stand in for !=
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function neq($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[neq], func_get_args());
}
const neq = __NAMESPACE__ . '\neq';
__PRIVATE__::$instance[neq] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x != $y;
});
// == != ==

// -- !== --
/**
 * nid, short for not the identity, or not strictly equals, a stand in for !==
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function nid($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[nid], func_get_args());
}
const nid = __NAMESPACE__ . '\nid';
__PRIVATE__::$instance[nid] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x !== $y;
});
// == !== ==

// -- > --
/**
 * gt, short for greater than, a stand in for >
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function gt($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[gt], func_get_args());
}
const gt = __NAMESPACE__ . '\gt';
__PRIVATE__::$instance[gt] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x > $y;
});
// == > ==

// -- >= --
/**
 * gte, short for greater than or equal to, a stand in for >=
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function gte($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[gte], func_get_args());
}
const gte = __NAMESPACE__ . '\gte';
__PRIVATE__::$instance[gte] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x > $y;
});
// == >= ==

// -- < --
/**
 * lt, short for greater than, a stand in for >
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function lt($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lt], func_get_args());
}
const lt = __NAMESPACE__ . '\lt';
__PRIVATE__::$instance[lt] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x < $y;
});
// == < ==

// -- <= --
/**
 * lte, short for greater than or equal to, a stand in for >=
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function lte($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lte], func_get_args());
}
const lte = __NAMESPACE__ . '\lte';
__PRIVATE__::$instance[lte] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x <= $y;
});
// == <= ==

// -- <=> --
/**
 * ufo, short for the space ship operator, or the three way comparison, a stand in for <=>
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return int|\Closure
 */
function ufo($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[lte], func_get_args());
}
const ufo = __NAMESPACE__ . '\ufo';
__PRIVATE__::$instance[lte] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x != $y ? $x > $y ? 1 : -1 : 0;
});
// == <=> ==

// -- && --
/**
 * andL, short for logical and, a stand in for &&
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function andL($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[andL], func_get_args());
}
const andL = __NAMESPACE__ . '\andL';
__PRIVATE__::$instance[andL] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x && $y;
});
// == && ==

// -- || --
/**
 * orL, short for logical or, a stand in for ||
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function orL($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[orL], func_get_args());
}
const orL = __NAMESPACE__ . '\orL';
__PRIVATE__::$instance[orL] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x || $y;
});
// == || ==

// -- xor --
/**
 * xorL, short for logical xor, a stand in for xor keyword
 * @sig ($x)->($y)-> boolean $z
 * @param mixed $x
 * @param mixed $y
 * @return boolean|\Closure
 */
function xorL($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[xorL], func_get_args());
}
const xorL = __NAMESPACE__ . '\xorL';
__PRIVATE__::$instance[xorL] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x xor $y;
});
// == xor ==

// -- ! --
/**
 * not, or invert, stand in for !
 * @sig ($x) -> boolean $z
 * @param mixed $x
 * @return boolean|\Closure
 */
function notL($x = null)
{
  return call_user_func_array(__PRIVATE__::$instance[notL], func_get_args());
}
const notL = __NAMESPACE__ . '\notL';
__PRIVATE__::$instance[notL] = __PRIVATE__::curryExactly1(function ($x) {
  return !$x;
});
// == ! ==


// -- & --
/**
 * andB, short for bitwise and, a stand in for &
 * @sig (int $x)->(int $y)-> int $z
 * @param int $x
 * @param int $y
 * @return int|\Closure
 */
function andB($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[andB], func_get_args());
}
const andB = __NAMESPACE__ . '\andB';
__PRIVATE__::$instance[andB] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x & $y;
});
// == & ==

// -- | --
/**
 * orB, short for logical or, a stand in for |
 * @sig (int $x)->(int $y)-> int $z
 * @param int $x
 * @param int $y
 * @return int|\Closure
 */
function orB($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[orB], func_get_args());
}
const orB = __NAMESPACE__ . '\orB';
__PRIVATE__::$instance[orB] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x | $y;
});
// == | ==

// -- ^ --
/**
 * xorL, short for logical xor, a stand in for xor keyword
 * @sig (int $x)->(int $y)-> int $z
 * @param int $x
 * @param int $y
 * @return int|\Closure
 */
function xorB($x = null, $y = null)
{
  return call_user_func_array(__PRIVATE__::$instance[xorB], func_get_args());
}
const xorB = __NAMESPACE__ . '\xorB';
__PRIVATE__::$instance[xorB] = __PRIVATE__::curryExactly2(function ($x, $y) {
  return $x ^ $y;
});
// == ^ ==

// -- ~ --
/**
 * notB, not binary, invert, or 2's compliment, a stand in for ~
 * @sig (int $x) -> int $z
 * @param int $x
 * @return int|\Closure
 */
function notB($x = null)
{
  return call_user_func_array(__PRIVATE__::$instance[notB], func_get_args());
}
const notB = __NAMESPACE__ . '\notB';
__PRIVATE__::$instance[notB] = __PRIVATE__::curryExactly1(function ($x) {
  return ~$x;
});
// == ~ ==