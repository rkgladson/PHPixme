<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:20 AM
 */

namespace PHPixme;

const Some = 'PHPixme\Some';
/**
 * @param $x - a non- null value
 * @return Some
 */
function Some($x)
{
    return new Some($x);
}

class Some extends Maybe
{
    private $done = true;
    protected $x;

    // -- Magic Methods --
    public function __construct($x)
    {
        $this->x = $x;
    }

    // == Magic Methods ==

    public function contains($x)
    {
        return $this->x === $x;
    }

    public function exists(callable $hof)
    {
        return (boolean) $hof($this->x);
    }

    public function get()
    {
        return $this->x;
    }

    // -- Natural Transformation Interface --
    /**
     * @param callable $hof
     * @return \PHPixme\Some|\PHPixme\None
     */
    public function find(callable $hof)
    {
        return $hof($this->x, 0, $this) ? $this : None();
    }

    public function filter(callable $hof)
    {
        return $hof($this->x, 0, $this) ?
            $this
            : None::getInstance();
    }

    public function filterNot(callable $hof)
    {
        return !$hof($this->x, 0, $this) ?
            $this
            : None::getInstance();
    }

    public function flatMap(callable $hof)
    {
        return Maybe($hof($this->x, 0, $this));
    }

    public function flatten()
    {
        return ($this->x);
    }

    public function fold($startVal, callable $hof)
    {
        return $hof($startVal, $this->get(), 0, $this);
    }

    public function isEmpty()
    {
        return false;
    }

    /**
     * @param callable $hof
     * @return Some
     */
    public function map(callable $hof)
    {
        return Some($hof($this->x, 0, $this));
    }

    public function toArray()
    {
        return [$this->x];
    }

    public function walk(callable $hof)
    {
        return $hof($this->get(), 0, $this);
    }

    // == Natural Transformation Interface ==

    // -- Iterator Interface --
    public function current()
    {
        return $this->done ? $this->x : null;
    }

    public function key()
    {
        return $this->done ? 0 : null;
    }

    public function next()
    {
        $this->done = true;
    }

    public function rewind()
    {
        $this->done = false;
    }

    public function valid()
    {
        return $this->done;
    }
    // == Iterator Interface ==

}