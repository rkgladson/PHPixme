<?php

namespace PHPixme;

/**
 * Class NothingQueryTrait
 * @package PHPixme
 * A boilerplate for implementors of {@see QueryInterface} that are a perceived nothing
 */
trait NothingQueryTrait
{
  /**
   * @inheritdoc
   * @see QueryInterface::isEmpty
   * Things perceived as nothings are always empty
   * @return bool
   */
  final public function isEmpty()
  {
    return true;
  }
  
  /**
   * @inheritdoc
   * @see QueryInterface::forAll
   * On perceived nothings, forAll is a vacuous truth
   * @return boolean
   */
  final public function forAll(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   * @see QueryInterface::forNone
   * On perceived nothings, forNone is a truth
   * @return boolean
   */
  final public function forNone(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   * @see QueryInterface::forSome
   * On perceived nothings, forSome is a falsehood
   * @return boolean
   */
  final public function forSome(callable $predicate)
  {
    return false;
  }

  /**
   * @inheritdoc
   * @see QueryInterface::find
   * This is an constant on perceived nothings
   * @return None
   */
  final public function find(callable $hof)
  {
    return None::getInstance();
  }
}