<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:19 AM
 */

namespace PHPixme;


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

    abstract public function contains($x);

    abstract public function exists(callable $hof);

    abstract public function forAll(callable $hof);


    /**
     * Gets whatever is contained
     * @return mixed - The value contained in subclass \Some
     * @throws \Exception - if of subclass None
     */
    abstract public function get();


    public function getOrElse($default)
    {
        return $this->isEmpty() ? $default : $this->get();
    }

    final public function isDefined()
    {
        return !$this->isEmpty();
    }

    public function orNull()
    {
        return $this->getOrElse(null);
    }

    public function orElse(callable $hof)
    {
        if ($this->isEmpty()) {
            $results = $hof();
            return $results instanceOf Maybe ?
                $results
                : Maybe($results);
        }
        return $this;
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

    // == NaturalTransformationInterface ==

}