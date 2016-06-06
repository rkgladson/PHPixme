<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 9:44 AM
 */

namespace PHPixme;

use PHPixme\exception\VacuousOffsetException;

/**
 * Class Seq
 * Seq is an 0 to N item monadic collection inspired by Scala's List and blended with PHP's ArrayObject.
 * It is an immutable collection, and any offset operators applied to it will produce a new Seq with the modification.
 * @package PHPixme
 */
class Seq implements
  CollectionInterface
  , MultipleStaticCreation
  , ImmutableOffsetAccess
  , ListInterface
  , FilterableInterface
  , ReducibleInterface
  , GroupableInterface
  , \Countable
{
  use AssertTypeTrait, ClosedTrait, ImmutableConstructorTrait;
  private $hash = [];
  private $keyR = [];
  private $keyRBackwards = [];
  private $length = 0;

  /**
   * Seq constructor.
   * @param \Traversable|array|CollectionInterface $arrayLike
   */
  public function __construct($arrayLike)
  {
    $this->assertOnce();
    $this->hash = __PRIVATE__::traversableToArray(__CONTRACT__::argIsATraversable($arrayLike));
    $this->keyR = array_keys($this->hash);
    $this->keyRBackwards = array_reverse($this->keyR);
    $this->length = count($this->hash);
  }


  /**
   * @param string|int $key
   * @param array $hash
   * @return bool
   * @codeCoverageIgnore
   */
  protected static function keyDefined(&$key, array &$hash)
  {
    return isset($hash[$key]) || array_key_exists($key, $hash);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public static function from($arrayLike)
  {
    return new static($arrayLike);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public static function of($head = null, ...$tail)
  {
    return new static(func_get_args());
  }

  /**
   * A shorthand to get the value. Return the value at the offset within the collection, or return null
   * @param mixed $offset
   * @return mixed|null
   */
  public function __invoke($offset)
  {
    return static::keyDefined($offset, $this->hash) ? $this->hash[$offset] : null;
  }

  /**
   * @inheritdoc
   */
  public function offsetGet($offset)
  {
    return static::keyDefined($offset, $this->hash)
      ? $this->hash[$offset]
      : null;
  }

  /**
   * @inheritdoc
   */
  public function offsetGetMaybe($offset)
  {
    return static::keyDefined($offset, $this->hash)
      ? Some($this->hash[$offset])
      : None();
  }

  /**
   * @inheritdoc
   */
  public function offsetGetAttempt($offset)
  {
    return static::keyDefined($offset, $this->hash)
      ? Success($this->hash[$offset])
      : Failure(new VacuousOffsetException($offset));
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function offsetSet($offset, $value)
  {
    $dupe = $this->hash;
    if (is_null($offset)) {
      $dupe[] = $value;
    } else {
      $dupe[$offset] = $value;
    }
    return new static($dupe);
  }

  /**
   * @inheritdoc
   */
  public function offsetAdjust($offset, callable $fn)
  {
    if (static::keyDefined($offset, $this->hash)) {
      $output = $this->hash;
      $output[$offset] = call_user_func($fn, $this->hash[$offset], $offset, $this);
      return new static($output);
    }
    return $this;
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function offsetUnset($offset)
  {
    $dupe = $this->hash;
    unset($dupe[$offset]);
    return new static($dupe);
  }

  /**
   * @inheritdoc
   */
  public function offsetExists($offset)
  {
    return static::keyDefined($offset, $this->hash);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function map(callable $hof)
  {
    $output = [];
    foreach (I($this->hash) as $key => $value) {
      $output[$key] = call_user_func($hof, $value, $key, $this);
    }
    return static::from($output);
  }

  /**
   * @inheritdoc
   * Yet another deviation form description.
   * While it keeps the contract, a Functor return, it requires $functor to be a MultipleStaticCreation implementor
   * in order to stay true to the Functor Type. Otherwise it substitutes itself, which can be seen as a Group
   * of Monads. In a strict language, it would make sense to only allow apply on its own kind, however
   * because of PHP's poor typing system, and the implications of having needlessly nested items,
   * the result is flattened.
   * @return MultipleStaticCreation
   */
  public function apply(FunctorInterface $functor) {
    $output = [];
    foreach (I($this->hash) as $hof) {
      foreach(($functor->map(__CONTRACT__::contentIsACallable($hof))) as $value) {
        $output[] = $value;
      }
    }

    return $functor instanceof MultipleStaticCreation
      ? $functor::from($output)
      : new static($output);
  }

  /**
   * @inheritdoc
   */
  public function filter(callable $hof)
  {
    $output = [];
    foreach (I($this->hash) as $key => $value) {
      if (call_user_func($hof, $value, $key, $this)) {
        $output[$key] = $value;
      }
    }
    return static::from($output);
  }

  /**
   * @inheritdoc
   */
  public function filterNot(callable $hof)
  {
    $output = [];
    foreach (I($this->hash) as $key => $value) {
      if (!(call_user_func($hof, $value, $key, $this))) {
        $output[$key] = $value;
      }
    }
    return static::from($output);
  }

  /**
   * Maps over a Seq who's $hof function returns a CollectionInterface or array and flattens the result
   * into a single sequence.
   * @param callable $hof ($value, $key, $this) -> CollectionInterface|SplFixedArray|ArrayObject|ArrayIterator|array
   * @return Seq
   */
  public function flatMap(callable $hof)
  {

    $output = [];
    foreach (I($this->hash) as $key => $value) {
      $intermediate = call_user_func($hof, $value, $key, $this);

      $array = __PRIVATE__::getArrayFrom($intermediate);
      $output[] = $array !== null
        ? $array
        // Because of self::getArrayFrom, this will ALWAYS throw its error
        : __CONTRACT__::returnIsA(CollectionInterface::class, $intermediate);
    }
    return static::from(call_user_func_array('array_merge', $output));
  }

  /**
   * Takes a Seq of nested CollectionInterface, SplFixedArray, ArrayObject, ArrayIterator or array, and flattens
   * it to a new Sequence
   * Note: This is a slight deviation from the CollectionInterface spec, as flatten should always receive the
   * the same type as itself. However this has been relaxed in this case, as we know that Seq is
   * can always hold more than one value. Therefor converting single collections into a sequence is
   * safe. As arrays are simply unwrapped Seq, we can also safely accept them for flattening.
   * The outcome will always be the expected result, even with the relaxation of the rules:
   * A de-nested Seq.
   * We do not, however, know if a traversable will ever terminate, so we will not attempt to flatten those.
   * If you have a nested sequence of transversable, iterate over them with ->flatMap using unary('iterator_to_array').
   * @return Seq
   */
  public function flatten()
  {
    $output = [];
    foreach (I($this->hash) as $value) {
      $array = __PRIVATE__::getArrayFrom($value);
      $output[] = $array !== null
        ? $array
        // Because of self::getArrayFrom, this will ALWAYS throw its error
        : __CONTRACT__::contentIsA(CollectionInterface::class, $value);
    }
    return static::from(call_user_func_array('array_merge', $output));
  }

  /**
   * @inheritdoc
   */
  public function fold(callable $hof, $startVal)
  {
    $output = $startVal;
    // No side effects for PHP 5.x
    foreach (I($this->hash) as $key => $value) {
      $output = call_user_func($hof, $output, $value, $key, $this);
    }
    return $output;
  }

  /**
   * @inheritdoc
   */
  public function foldRight(callable $hof, $startVal)
  {
    $output = $startVal;
    // No side effects for PHP 5.x
    foreach (I($this->keyRBackwards) as $key) {
      $output = call_user_func($hof, $output, $this->hash[$key], $key, $this);
    }
    return $output;
  }

  /**
   * @inheritdoc
   */
  public function forAll(callable $predicate)
  {
    // No side effects for PHP 5.x
    foreach (I($this->hash) as $key => $value) {
      if (!(call_user_func($predicate, $value, $key, $this))) {
        return false;
      }
    }
    return true;
  }

  /**
   * @inheritdoc
   */
  public function forSome(callable $predicate)
  {
    // No side effects for PHP 5.x
    foreach (I($this->hash) as $key => $value) {
      if (call_user_func($predicate, $value, $key, $this)) {
        return true;
      }
    }
    return false;
  }

  /**
   * @inheritdoc
   */
  public function forNone(callable $predicate)
  {
    // No side effects for PHP 5.x
    foreach (I($this->hash) as $key => $value) {
      if (call_user_func($predicate, $value, $key, $this)) {
        return false;
      }
    }
    return true;
  }

  /**
   * @inheritdoc
   */
  public function reduce(callable $hof)
  {
    if ($this->length < 1) {
      throw new \LengthException('Cannot reduce an empty Seq. Behavior is undefined.');
    }
    // Make a copy to shift the first value
    $keyR = $this->keyR;
    $output = $this->hash[array_shift($keyR)];
    foreach ($keyR as $key) {
      $output = call_user_func($hof, $output, $this->hash[$key], $key, $this);
    }
    return $output;
  }

  /**
   * @inheritdoc
   */
  public function reduceRight(callable $hof)
  {
    if ($this->length < 1) {
      throw new \LengthException('Cannot reduceRight an empty Seq. Behavior is undefined.');
    }
    // Make a copy to shift the first value
    $keyR = $this->keyRBackwards;
    $output = $this->hash[array_shift($keyR)];
    foreach ($keyR as $key) {
      $output = call_user_func($hof, $output, $this->hash[$key], $key, $this);
    }
    return $output;
  }

  /**
   * Union join the values of one or more with this Seq and return a new Seq
   * @param array $arrayLikeN An array of transversable
   * @return Seq
   */
  public function union(...$arrayLikeN)
  {
    $output = [$this->hash];
    foreach ($arrayLikeN as $arg => $value) {
      $output[] = __PRIVATE__::traversableToArray(__CONTRACT__::argIsATraversable($value, $arg));
    }
    return static::from(call_user_func_array('array_merge', $output));
  }


  /**
   * @inheritdoc
   */
  public function find(callable $hof)
  {
    // No side effects for PHP 5.x
    foreach (I($this->keyR) as $key) {
      if (call_user_func($hof, $this->hash[$key], $key, $this)) {
        return Some($this->hash[$key]);
      }
    }
    return None();
  }

  /**
   * @inheritdoc
   */
  public function walk(callable $hof)
  {
    // No side effects for PHP 5.x
    foreach (I($this->keyR) as $key) {
      call_user_func($hof, $this->hash[$key], $key, $this);
    }
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function headMaybe()
  {
    return $this->isEmpty() ? None() : Some($this->hash[$this->keyR[0]]);
  }

  /**
   * @inheritdoc
   */
  public function head()
  {
    return $this->isEmpty() ? null : $this->hash[$this->keyR[0]];
  }

  /**
   * @inheritdoc
   */
  public function tail()
  {
    return $this::from(array_slice($this->hash, 1, null, true));
  }

  /**
   * Finds the first occurrence of a key with the value of the thing, returning -1 when not found.
   * If your array has a key of -1, you should use find instead.
   * @param $thing
   * @return Some|None
   */
  public function indexOf($thing)
  {
    $key = array_search($thing, $this->hash, true);
    return $key === false ? None() : Some($key);
  }


  /**
   * @inheritdoc
   * @return Seq
   */
  public function partition(callable $fn)
  {
    $output = ["false" => [], "true" => []];
    foreach (I($this->hash) as $key => $value) {
      $output[call_user_func($fn, $value, $key, $this) ? "true" : "false"][] = $value;
    }
    return static::from([
      "false" => static::from($output["false"])
      , "true" => static::from($output["true"])
    ]);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function partitionWithKey(callable $fn)
  {
    $output = ["false" => [], "true" => []];
    foreach (I($this->hash) as $key => $value) {
      $output[call_user_func($fn, $value, $key, $this) ? "true" : "false"][] = [$key, $value];
    }
    return static::from([
      "false" => static::from($output["false"])
      , "true" => static::from($output["true"])
    ]);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function group(callable $fn)
  {
    $output0 = [];
    foreach (I($this->hash) as $key => $value) {
      $output0[call_user_func($fn, $value, $key, $this)][] = $value;
    }
    $output1 = [];
    foreach ($output0 as $groupKey => $group) {
      $output1[$groupKey] = static::from($group);
    }
    return static::from($output1);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function groupWithKey(callable $fn)
  {
    $output0 = [];
    foreach (I($this->hash) as $key => $value) {
      $output0[call_user_func($fn, $value, $key, $this)][] = [$key, $value];
    }
    $output1 = [];
    foreach ($output0 as $groupKey => $group) {
      $output1[$groupKey] = static::from($group);
    }
    return static::from($output1);
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function drop($number = 0)
  {
    return $this::from(array_slice($this->hash, $number, null, true));
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function dropRight($number = 0)
  {
    return $this::from(array_slice($this->hash, 0, -1 * $number, true));
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function take($number = 0)
  {
    return $this::from(array_slice($this->hash, 0, $number, true));
  }

  /**
   * @inheritdoc
   * @return Seq
   */
  public function takeRight($number = 0)
  {
    return $this::from(array_slice($this->hash, -1 * $number, null, true));
  }

  /**
   * @inheritdoc
   */
  public function toArray()
  {
    return $this->hash;
  }

  /**
   * Unzips the values from the keys and returns it as a Seq
   * @return self
   */
  public function values()
  {
    return static::from(array_values($this->hash));
  }

  /**
   * Unzips the keys from the values and returns it as a Seq
   * @return self
   */
  public function keys()
  {
    return static::from($this->keyR);
  }

  /**
   * @inheritdoc
   */
  public function isEmpty()
  {
    return $this->length === 0;
  }

  /**
   * Gets the number of the contained in the collection
   * @return integer
   */
  public function count()
  {
    return $this->length;
  }

  /**
   * Converts the sequence into a delimited string
   * @param string $glue
   * @return string
   */
  public function toString($glue = ',')
  {
    return implode($glue, $this->hash);
  }

  /**
   * Encodes the Seq to a json string
   * @return string
   */
  public function toJson()
  {
    return json_encode($this->hash);
  }

  /**
   * @inheritdoc
   * @return \ArrayObject
   */
  public function toArrayAccess()
  {
    return new \ArrayObject($this->hash);
  }

  /**
   * Take the order of the Seq, and return the reverse order and preserves keys to data.
   * @return Seq
   */
  public function reverse()
  {
    return $this::from(array_reverse($this->hash, true));
  }

  /**
   * Returns a clone that represents the hash as an ArrayIterator
   * @return \ArrayIterator
   */
  public function getIterator()
  {
    return new \ArrayIterator($this->hash);
  }
}
