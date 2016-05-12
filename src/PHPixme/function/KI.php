<?php
namespace PHPixme;

/**
 * KI or Kite, or a Kestrel that ate an Idiot Bird, and can represent false.
 * It takes an argument, and ignores it, always returning the argument passed to it later.
 * @param $ignored = This parameter will be ignored
 * @return \Closure
 * @sig ignored -> second -> second
 */
function KI($ignored = null)
{
  return function ($second) {
    return $second;
  };
}
const KI = __NAMESPACE__ . '\KI';

