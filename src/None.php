<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 10:21 AM
 */

namespace PHPixme;

const None = 'PHPixme\None';
function None()
{
    return None::getInstance();
}

/**
 * Class None
 * @package PHPixme
 * This class is a singleton! Please us it's Function companion for eas of use!
 */
class None extends Maybe
{
    protected static $instance = null;

    public function contains($x)
    {
        return false;
    }
    public function exists(callable $hof)
    {
        return false;
    }

    public function get()
    {
        throw new \Exception('Cannot get on None!');
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    // -- Natural Transformation Static --
    public static function of(...$args)
    {
        return static::getInstance();
    }

    public static function from($args)
    {
        return static::getInstance();
    }
    // == Natural transformation Static ==

    // -- Magic Methods --
    protected function __clone()
    {
        // This space intentionally left blank
    }

    protected function __wakeup()
    {
        // This space intentionally left blank
    }

    protected function __construct()
    {
        // This space intentionally left blank
    }

    // == Magic Methods ==

    // -- Natural Transformation interface methods --

    public function isEmpty()
    {
        return true;
    }

    public function find(callable $hof)
    {
        return $this;
    }

    public function flatten()
    {
        return $this;
    }

    public function flatMap(callable $hof)
    {
        return $this;
    }
    public function filter(callable $hof)
    {
        return $this;
    }

    public function filterNot(callable $hof)
    {
        return $this;
    }


    public function fold($startVal, callable $hof)
    {
        return $startVal;
    }

    public function map(callable $hof)
    {
        return $this;
    }


    public function reduce(callable $hof)
    {
        throw new \InvalidArgumentException('Cannot reduce on None. Behaviour is undefined');
    }

    public function toArray()
    {
        return [];
    }

    public function walk(callable $hof)
    {
        return $this;
    }

    // == Natural Transformation interface methods ==

    // -- Iterator interface methods--
    public function key()
    {
        return null;
    }

    /**
     * None is always at it's end
     * @return false
     */
    public function valid()
    {
        return false;
    }

    public function next()
    {
        // This space is intentionally left blank
    }

    public function rewind()
    {
        // This space is intentionally left blank
    }

    public function current()
    {
        return null;
    }
    // == Iterator interface methods==

}