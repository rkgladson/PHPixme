<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 2:11 PM
 */

namespace PHPixme;


trait SingleIteratorTrait
{
  protected $done = true;
  // -- Iterator Interface --

  public function key()
  {
    return $this->done ? null : 0;
  }

  public function next()
  {
    $this->done = true;
  }

  public function rewind()
  {
    $this->done = false;
  }

  public function valid()
  {
    return !$this->done;
  }
  // == Iterator Interface ==

}