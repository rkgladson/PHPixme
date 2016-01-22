<?php
namespace PHPixme;
const Maybe = __NAMESPACE__.'\Maybe';
/**
 * Takes a value and wraps it in a Maybe family object
 * @param $x - the maybe existing value
 * @return \PHPixme\None|\PHPixme\Some
 */
function Maybe($x = null)
{
    return (
        !isset($x) || is_null($x) ||
        (is_array($x) && count($x) === 0)
    ) ?
        None()
        : Some($x);
}



const None = __NAMESPACE__.'\None';
function None()
{
    return None::getInstance();
}

const Some = __NAMESPACE__.'\Some';
/**
 * @param $x - a non- null value
 * @return \PHPixme\Some
 */
function Some($x)
{
    return new Some($x);
}