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

abstract class Maybe implements NaturalTransformationInterface, \Iterator
{
    private $done;
    // -- Natural Transformation Interface Statics --
    static function of(...$args)
    {
        return Maybe($args[0]);
    }

    static function from($arg)
    {
        return Maybe($arg);
    }
    // == Natural Transformation Interface Statics ==

    abstract function contains($x);
    abstract function exists(callable $hof);
    /**
     * Gets whatever is contained
     * @return mixed - The value contained in subclass \Some
     * @throws \Exception - if of subclass None
     */
    abstract function get();


    public function getOrElse($default)
    {
        return $this->isEmpty() ? $default : $this->get();
    }

    public function orNull()
    {
        return $this->getOrElse(null);
    }

    final public function isDefined()
    {
        return !$this->isEmpty();
    }

    public function toSeq()
    {
        return Seq($this->toArray());
    }

    // -- NaturalTransformationInterface --



    /**
     * This form of reduce simply will return get. This will throw an error on None,
     * Since this undefined behavior to reduce on a empty collection
     * @param callable $hof
     * @return mixed
     */
    public function reduce(callable $hof) {
        return $this->get();
    }

}