<?php
namespace PHPixme;
/**
 * @param mixed $x
 * @throws \Error|\Exception|Pot
 * @sig $x -!-> $x|Pot($x)
 * An adapter to throw anything, not just exceptions. It is an identiy on \Throwables
 */
function toss($x)
{
  throw !($x instanceof \Exception || $x instanceof \Error) ? Pot::of($x) : $x;
}
const toss = __NAMESPACE__ . '\toss';
