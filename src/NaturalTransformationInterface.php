<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/4/2016
 * Time: 9:59 AM
 */

namespace PHPixme;

interface NaturalTransformationInterface
{
    // Static constructors:
    /**
     * Transfer the data from one container to another
     * @param \Traversable|array|NaturalTransformationInterface $traversable
     * @return static - A new instance of the class
     */
    public static function from($traversable);

    /**
     * A static function that accepts a list of items that will be contained by the class
     * Note: a implementing class will discard any excess if there is a container limit
     * @param mixed[] ...$items - A list of items, defining like Array
     * @return static - A new instance of the class
     */
    public static function of(...$items);

    /**
     * Fold across this class
     * @param mixed $startVal
     * @param callable $hof
     * @return mixed - whatever the last cycle of $hof returns
     */
    public function fold($startVal, callable $hof);

    /**
     * Reduce across this class
     * @param callable $hof
     * @return mixed - whatever $hof returned last, or the only item contained
     * @throws \OutOfRangeException - Throws range exceptions when the container's value contains less than 1
     */
    public function reduce(callable $hof);

    /**
     * Map across the container
     * @param callable $hof
     * @return static
     */
    public function map(callable $hof);

    public function flatMap(callable $hof);
    public function flatten();

    /**
     * Filter the contents of the container
     * @param callable $hof
     * @return static
     */
    public function filter(callable $hof);

    public function filterNot(callable $hof);

    /**
     * Preform $hof over the container
     * @param callable $hof
     * @return null
     */
    public function walk(callable $hof);

    /**
     * Combine the data sets with the container
     * @param \Traversable[]|array[]|\PHPixme\NaturalTransformationInterface[] ...$traversableR
     * @return static
     */
    // Fixme: doesn't apply to single item collections
//    public function union(...$traversableR);

    /**
     * Converts the container to an array, in any structure that is appropriate within that array
     * @return array
     */
    public function toArray();

    /**
     * Is the container empty?
     * @return bool
     */
    public function isEmpty();

    /**
     * Search the container
     * @param callable $hof
     * @return \PHPixme\Some|\PHPixme\None
     */
    public function find(callable $hof);
}