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
    public static function getInstance() {
        return static::$instance;
    }
    public static function of(...$args)
    {
        return static::getInstance();
    }

    public static function from($args)
    {
        return static::getInstance();
    }

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


    public function walk(callable $hof)
    {
        return static::getInstance();
    }

    public function map(callable $hof)
    {
        return static::getInstance();
    }

    public function reduce(callable $hof)
    {
        throw new \InvalidArgumentException('Cannot reduce on None. Behaviour is undefined');
    }

    public function fold(callable $hof, $startVal)
    {
        return $startVal;
    }
    public function filter(callable $hof)
    {
        // TODO: Implement filter() method.
    }

    public function find(callable $hof)
    {
        return true;
    }

    public function union(...$traversableR)
    {
        // TODO: Implement union() method.
    }

    public function toArray()
    {
        return [];
    }

    public function get()
    {
        throw new \Exception('Cannot get on None!');
    }

    public function isEmpty()
    {
        return true;
    }
}