<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 1:19 PM
 */

namespace PHPixme;

const Pot = Pot::class;
/**
 * @param $data
 * @return Pot
 */
function Pot($data) {
  return Pot::of($data);
}