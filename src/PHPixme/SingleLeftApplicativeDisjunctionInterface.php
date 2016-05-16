<?php

namespace PHPixme;


interface SingleLeftApplicativeDisjunctionInterface
{
  /**
   * Returns a 'left hand side' representation of a DisjunctionInterface
   * @param $item
   * @return DisjunctionInterface
   */
  public static function ofLeft($item);
}