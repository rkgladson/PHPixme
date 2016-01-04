<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 9:44 AM
 */

namespace PHPixme;


class Seq extends \ArrayIterator implements NaturalTransformationInterface
{
    private $array = [];

    /**
     * Seq constructor.
     * @param \Traversable|array|\PHPixme\NaturalTransformationInterface $arrayLike
     */
    public function __construct($arrayLike)
    {
        if ($arrayLike instanceof NaturalTransformationInterface) {
            $this->array = $arrayLike->toArray();
        }
        __assertTraversable($arrayLike);
        if (is_array($arrayLike)) {
            $this->array = $arrayLike;
        } else {
            foreach ($arrayLike as $key => $value) {
                $this->array[$key] = $value;
            }
        }
        parent::__construct($this->array);
    }

    /**
     * Calls the constructor with the array like parameter
     * @param $arrayLike
     * @return Seq
     */
    public static function from($arrayLike)
    {
        return new Seq($arrayLike);
    }

    public static function of(...$args)
    {
        return new Seq($args);
    }

    public function __invoke($index)
    {
        return $this->array[$index];
    }

    public function map(callable $hof)
    {
        $output = [];
        foreach ($this->array as $key => $value) {
            $output[$key] = $hof($value, $key, $this);
        }
        return $this::from($output);
    }

    public function filter(callable $hof)
    {
        $output = [];
        foreach ($this->array as $key => $value) {
            if ($hof($value, $key, $this)) {
                $output[$key] = $value;
            }
        }
        return $this::from($output);
    }

    public function fold(callable $hof, $startVal)
    {
        $output = $startVal;
        foreach ($this->array as $key => $value) {
            $output = $hof($output, $value, $key, $this);
        }
        return $output;
    }

    public function reduce(callable $hof)
    {
        if (($length = count($this->array)) < 1) {
            throw new \OutOfRangeException('Cannot reduce a set of 0, this behavior is undefined');
        }
        $keyR = array_keys($this->array);
        $output = $this->array[array_shift($keyR)];
        foreach ($keyR as $key) {
            $output = $hof($output, $this->array[$key], $key, $this);
        }
        return $output;
    }

    public function union(...$arrayLikeN)
    {
        $output = $this->array;
        foreach ($arrayLikeN as $arrayLike) {
            __assertTraversable($arrayLike);
            foreach ($arrayLike as $key => $value) {
                $output[$key] = $value;
            }
        }
        return $this::from($output);
    }

    public function find(callable $hof)
    {
        $found = null;
        foreach (array_keys($this->array) as $key) {

            if ($hof($this->array[$key], $key, $this)) {
                $found = $this->array[$key];
                break;
            }
        }
        return Maybe($found);
    }

    public function walk(callable $hof)
    {
        foreach (array_keys($this->array) as $key) {
            $hof($this->array[$key], $key, $this);
        }
    }

    public function head()
    {
        return $this->array[array_keys($this->array)[0]];
    }

    public function tail()
    {
        if (count($this->array) > 1) {
            $tail = $this->array;
            array_shift($tail);
            return $this::from($tail);
        }
        return $this::from([]);
    }

    public function indexOf($thing)
    {
        $key = array_search($thing, $this->array, true);
        return $key === false ? -1 : $key;
    }

    public function partition($hof)
    {
        __assertCallable($hof);
        $output = [/*false =>*/
            [],/*true =>*/
            []];
        foreach ($this->array as $key => $value) {
            $output[(boolean)$hof($value, $key, $this) ? 1 : 0][$key] = $value;
        }
        return $this::from($output);
    }

    public function group($hof)
    {
        __assertCallable($hof);
        $output = [];
        foreach ($this->array as $key => $value) {
            $groupKey = (string)$hof($value, $key, $this);
            if (!is_array($output[$groupKey])) {
                $output[$groupKey] = [];
            }
            $output[$groupKey][$key] = $value;
        }
        return $this::from($output);
    }

    public function drop($number = 0)
    {
        return $this::from(array_slice($this->array, $number, null, true));
    }

    public function dropRight($number = 0)
    {
        return $this::from(array_slice($this->array, 0, -1 * $number, true));
    }

    public function take($number = 0)
    {
        return $this::from(array_slice($this->array, 0, $number, true));
    }

    public function takeRight($number = 0)
    {
        return $this::from(array_slice($this->array, -1 * $number, null, true));
    }

    public function toArray()
    {
        return $this->array;
    }

    public function isEmpty()
    {
        return empty($this->array);
    }

    public function toString($glue = ',') {
        return implode($glue, $this->array);
    }
    public function toJson() {
        return json_encode($this->array);
    }
    public function reverse() {
        return $this::from(array_reverse($this->array, true));
    }

}