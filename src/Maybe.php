<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:19 AM
 */

namespace PHPixme;

const Maybe = 'PHPixme\Maybe';
/**
 * Takes a value and wraps it in a Maybe family object
 * @param $x - the maybe existing value
 * @return \PHPixme\None|\PHPixme\Some
 */
function Maybe($x)
{
    return (
        !isset($x) || is_null($x) ||
        (is_array($x) && count($x) === 0)
    ) ?
        None()
        : Some($x);
}

abstract class Maybe implements NaturalTransformationInterface
{
    static function of(...$args)
    {
        return Maybe($args[0]);
    }

    static function from($arg)
    {
        return Maybe($arg);
    }

    abstract function isEmpty();
    public function toSeq() {
        return Seq($this->toArray());
    }
    public function getOrElse()
    {
        return $this->isEmpty() ? $this : None();
    }
}