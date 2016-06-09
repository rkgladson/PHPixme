<?php
namespace PHPixme;

/**
 * Class HeadTypeTrait
 * Place into
 * @package PHPixme
 */
trait RootTypeTrait
{
  use TypeTrait;
  /**
   * A helper function to retrieve the name of the parent class
   * @return string
   */
  final static public function rootType() {
    return self::class;
  }

  /**
   * @param mixed $unknown
   * @return mixed some child of the parent class
   * @throws exception\InvalidReturnException
   */
  final public static function assertRootType($unknown) {
    return __CONTRACT__::returnIsA(self::class, $unknown);
  }
}