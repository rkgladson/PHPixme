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
    protected $x;

    public function __construct($x)
    {
        $this->x = $x;
    }

    public function isEmpty()
    {
        return false;
    }

    public function get()
    {
        return $this->x;
    }
    public function toArray()
    {
        return [$this->x];
    }
    public function union(...$traversableR)
    {
        // TODO: Implement union() method.
    }
    public function filter(callable $hof)
    {
        // TODO: Implement filter() method.
    }
    public function reduce(callable $hof)
    {
        // TODO: Implement reduce() method.
    }
    public function map(callable $hof)
    {
        // TODO: Implement map() method.
    }
    public function fold(callable $hof, $startVal)
    {
        // TODO: Implement fold() method.
    }
    public function find(callable $hof)
    {
        // TODO: Implement find() method.
    }
    public function walk(callable $hof)
    {
        // TODO: Implement walk() method.
    }


}