<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/6/2016
 * Time: 3:05 PM
 */

namespace PHPixme;

/**
 * Class Valid
 * @package PHPixme
 */
class Valid extends Validate implements
  RightHandSideType

{
  use RightHandedTrait;
  const shortName = 'valid';

  /** @inheritdoc */
  public function merge()
  {
    return $this->value;
  }

  /** @inheritdoc */
  public function getIterator()
  {
    yield 0 => $this->value;
  }

  /** @inheritdoc */
  public function toArray()
  {
    return [static::shortName => $this->value];
  }

  /** @inheritdoc */
  public function toUnbiasedDisjunctionInterface()
  {
    return Either::ofRight($this->value);
  }

  /** @inheritdoc */
  public function count()
  {
    return 1;
  }

  /** @inheritdoc */
  public function isEmpty()
  {
    return false;
  }

  /** @inheritdoc */
  public function map(callable $hof)
  {
    return new static(call_user_func($hof, $this->value, 0, $this));
  }

  /** @inheritdoc */
  public function apply(FunctorInterface $functor)
  {
    return $functor->map(__CONTRACT__::contentIsACallable($this->value));
  }

  /** @inheritdoc */
  public function flatMap(callable $fn)
  {
    return Validate::assertType(call_user_func($fn, $this->value, 0, $this));
  }

  /** @inheritdoc */
  public function flatten()
  {
    return __CONTRACT__::contentIsA(Validate::class, $this->value);
  }

  /** @inheritdoc */
  public function fold(callable $hof, $startValue)
  {
    return call_user_func($hof, $startValue, $this->value, 0, $this);
  }

  /** @inheritdoc */
  public function foldRight(callable $hof, $startValue)
  {
    return call_user_func($hof, $startValue, $this->value, 0, $this);
  }

  /** @inheritdoc */
  public function forAll(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->value, 0, $this);
  }

  /** @inheritdoc */
  public function forNone(callable $predicate)
  {
    return !call_user_func($predicate, $this->value, 0, $this);
  }

  /** @inheritdoc */
  public function forSome(callable $predicate)
  {
    return (boolean)call_user_func($predicate, $this->value, 0, $this);
  }

  /** @inheritdoc */
  public function find(callable $hof)
  {
    return call_user_func($hof, $this->value, 0, $this) ? new Some($this->value) : None::getInstance();
  }

  /** @inheritdoc */
  public function walk(callable $hof)
  {
    call_user_func($hof, $this->value, 0, $this);
    return $this;
  }
}