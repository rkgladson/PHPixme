<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/12/2016
 * Time: 12:02 PM
 */

namespace PHPixme;
/**
 * Returns the placeholder instance that is used for placing gaps in curry
 * @return \stdClass
 */
function _()
{
  return __PRIVATE__::placeholder();
}