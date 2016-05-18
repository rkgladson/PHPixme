<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/16/2016
 * Time: 1:08 PM
 */

namespace PHPixme;

/**
 * Interface SwappableDisjunctionInterface
 * Denotes a Disjunction Type with no distinction of what may be held between the tracks.
 * @package PHPixme
 */
interface SwappableDisjunctionInterface
{
  /**
   * Converts a 'left hand side' to a 'right hand side', or a 'right hand side' to a 'left hand side'
   * @return SwappableDisjunctionInterface
   */
  public function swap();
  
}