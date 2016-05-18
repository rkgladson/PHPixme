<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/17/2016
 * Time: 10:20 AM
 */

namespace PHPixme;

/**
 * Class NothingCollectionTrait
 * A boilerplate for { @see CollectionInterface } that are a perceived nothing by the interface.
 * @package PHPixme
 */
trait NothingCollectionTrait
{
  /**
   * @inheritdoc
   * @see CollectionInterface::isEmpty
   * Things perceived as nothings are always empty
   * @return bool
   */
  final public function isEmpty()
  {
    return true;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::map
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function map(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::flatMap
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function flatMap(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::flatten
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function flatten()
  {
    return $this;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::fold
   * This is a no-op on perceived nothings
   * @return mixed
   */
  final public function fold(callable $hof, $startVal)
  {
    return $startVal;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::foldRight
   * This is a no-op on perceived nothings
   * @return mixed
   */
  final public function foldRight(callable $hof, $startVal)
  {
    return $startVal;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::forAll
   * On perceived nothings, forAll is a vacuous truth
   * @return boolean
   */
  final public function forAll(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::forNone
   * On perceived nothings, forNone is a truth
   * @return boolean
   */
  final public function forNone(callable $predicate)
  {
    return true;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::forSome
   * On perceived nothings, forSome is a falsehood
   * @return boolean
   */
  final public function forSome(callable $predicate)
  {
    return false;
  }


  /**
   * @inheritdoc
   * @see CollectionInterface::walk
   * This is a no-op on perceived nothings
   * @return $this
   */
  final public function walk(callable $hof)
  {
    return $this;
  }

  /**
   * @inheritdoc
   * @see CollectionInterface::find
   * This is an constant on perceived nothings
   * @return None
   */
  final public function find(callable $hof)
  {
    return None::getInstance();
  }
}