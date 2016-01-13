<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/13/2016
 * Time: 10:12 AM
 */

namespace tests\PHPixme;
require_once "tests/PHPixme_TestCase.php";
use PHPixme as P;
const Closure = '\Closure';
class FunctionalTest extends PHPixme_TestCase
{
    public function test_curry() {
        $countArgs = function () {return func_num_args();};
        $param3 = P\curry(3, $countArgs);

        $this->assertInstanceOf(
            Closure
            , $param3
            , 'Curry should return a closure'
        );
        $this->assertInstanceOf(
            Closure
            ,  $param3(1)
            , 'Curried functions should be still a closure when partially applied'
        );
        $this->assertEquals(
            3
            , $param3(1,2,3)
            , 'Curried functions should run when the minimum arguments are applied'
        );
        $this->assertEquals(
            4
            , $param3(1,2,3,4)
            , 'Curried functions may pass more than the minimum arity is passed'
        );
        $this->assertEquals(
            3
            , $param3(1)->__invoke(2)->__invoke(3)
            , 'Curried functions should be able to be chained'
        );
        $curry2 = P\curry(2);
        $this->assertInstanceOf(
            Closure
            , $curry2
            , 'The curry function itself should be curried'
        );
        $this->assertInstanceOf(
            Closure
            , $curry2($countArgs)
            , 'The partially applied curry function should produce a closure'
        );
        $this->assertEquals(
            2
            , $curry2($countArgs)->__invoke(1,2)
            , 'The partially applied version of curry should behave just like the non-partially applied one'
        );
    }
}
