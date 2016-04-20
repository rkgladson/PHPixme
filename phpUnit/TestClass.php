<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/20/2016
 * Time: 2:40 PM
 */

namespace tests\PHPixme;

/**
 * Class TestClass
 * @package tests\PHPixme
 * A class to assist in testing properties of object functions
 */
class TestClass
{
  public $value = true;

  static public function testStatic()
  {
    return func_get_args();
  }

  public function getArgs()
  {
    return func_get_args();
  }

  public function countArgs()
  {
    return func_num_args();
  }
}

class invokable
{
  public function __invoke($x)
  {
    return $x;
  }
}
