<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 9:44 AM
 */

namespace PHPixme;

class Seq implements
  CollectionInterface
  , MultipleStaticCreation
  , ImmutableOffsetAccess
  , FilterableInterface
  , ReducibleInterface
  , \Countable
{
  use AssertType;
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
    $this->hash = static::arrayLikeToArray($arrayLike);
    $this->keyR = array_keys($this->hash);
    $this->keyRBackwards = array_reverse($this->keyR);
    $this->length = count($this->hash);
  }

  /**
   * A helper function to assist conversion of Collection and Traversable to arrays.
   * It also is the identity function on an array
   * @internal
   * @param $arrayLike
   * @return array
   */
  protected static function arrayLikeToArray($arrayLike)
  {
    if ($arrayLike instanceof CollectionInterface) {
      return $arrayLike->toArray();
    }
    __PRIVATE__::assertTraversable($arrayLike);
    if (is_array($arrayLike)) {
      return $arrayLike;
    }

    $output = [];
    foreach ((clone $arrayLike) as $key => $value) {
      $output[$key] = $value;
    }
    return $output;
  }

  /**
   * @inheritdoc
   */
  public static function from($arrayLike)
  {
    return new static($arrayLike);
  }

  /**
   * @inheritdoc
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
    return isset($this->hash[$offset]) ? $this->hash[$offset] : null;
  }


  /**
   * @inheritdoc
   */
  public function offsetGet($offset)
  {
    return isset($this->hash[$offset]) ? $this->hash[$offset] : null;
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
  public function offsetApply($offset, callable $fn)
  {
    if (isset($this->hash[$offset])) {
      $output = $this->hash;
      $output[$offset] = call_user_func($fn, $this->hash[$offset]);
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
    return isset($this->hash[$offset]);
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
   * @param callable $hof ($value, $key, $this) -> CollectionInterface|array
   * @return Seq
   */
  public function flatMap(callable $hof)
  {
    return $this->map($hof)->flatten();
  }

  /**
   * Takes a Seq of nested Collection or array, and flattens it to a new Sequence
   * Note: This is a slight deviation from the CollectionInterface spec, as flatten should always receive the
   * the same type as itself. However this has been relaxed in this case, as we know that Seq is
   * can always hold more than one value. Therefor converting single collections into a sequence is
   * safe. As arrays are simply unwrapped seq, we can also safely accept them for flattening.
   * The outcome will always be the expected result, even with the relaxation of the rules:
   * A de-nested Seq.
   * We do not, however, know if a traversable will ever terminate, so we will not attempt to flatten those.
   * If you have a nested sequence of transversable, iterate over them in ->flatMap and return an array.
   * @return Seq
   */
  public function flatten()
  {
    return new static(
      call_user_func_array(
        'array_merge'
        , array_map(
          function ($value) {
            return (is_array($value) ? $value : __PRIVATE__::assertCollection($value)->toArray());
          }
          , $this->hash
        )
      )
    );
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

  public function union(...$arrayLikeN)
  {
    array_unshift($arrayLikeN, $this->hash);
    return static::from(call_user_func_array('array_merge', map(function ($value) {
      return static::arrayLikeToArray($value);
    }, $arrayLikeN)));
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

  public function head()
  {
    return $this->isEmpty() ? null : $this->hash[$this->keyR[0]];
  }

  public function tail()
  {
    if ($this->length > 1) {
      $tail = $this->hash;
      array_shift($tail);
      return $this::from($tail);
    }
    return $this::from([]);
  }

  public function indexOf($thing)
  {
    $key = array_search($thing, $this->hash, true);
    return $key === false ? -1 : $key;
  }

  public function partition(callable $hof)
  {
    $true = [];
    $false = [];
    // No side effects for PHP 5.x
    foreach (I($this->hash) as $key => $value) {
      if (call_user_func($hof, $value, $key, $this)) {
        $true[$key] = $value;
      } else {
        $false[$key] = $value;
      }
    }
    return static::of(static::from($false), static::from($true));
  }

  public function group($hof)
  {
    __PRIVATE__::assertCallable($hof);
    return static::from(array_map(
      static::class . '::from'
      , $this->fold(
      function ($output, $value, $key) use ($hof) {
        $groupKey = (string)call_user_func($hof, $value, $key, $this);
        if (!isset($output[$groupKey])) {
          $output[$groupKey] = [];
        }
        $output[$groupKey][$key] = $value;
        return $output;
      }
      , []
    )
    ));
  }

  public function drop($number = 0)
  {
    return $this::from(array_slice($this->hash, $number, null, true));
  }

  public function dropRight($number = 0)
  {
    return $this::from(array_slice($this->hash, 0, -1 * $number, true));
  }

  public function take($number = 0)
  {
    return $this::from(array_slice($this->hash, 0, $number, true));
  }

  public function takeRight($number = 0)
  {
    return $this::from(array_slice($this->hash, -1 * $number, null, true));
  }

  public function toArray()
  {
    return $this->hash;
  }

  public function values()
  {
    return static::from(array_values($this->hash));
  }

  public function keys()
  {
    return static::from($this->keyR);
  }


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

  public function toString($glue = ',')
  {
    return implode($glue, $this->hash);
  }

  public function toJson()
  {
    return json_encode($this->hash);
  }

  /**
   * @inheritdoc
   */
  public function toArrayObject()
  {
    return new \ArrayObject($this->hash);
  }

  public function reverse()
  {
    return $this::from(array_reverse($this->hash, true));
  }


  public function getIterator()
  {
    return new \ArrayIterator($this->hash);
  }
}
