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
        $this->assertStringEndsWith(
            '\curry'
            , P\curry
            , 'Ensure the constant is assigned to its function name'
        );
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

    public function test_nAry() {
        $countArgs = function () { return func_num_args(); };
        $this->assertStringEndsWith(
            '\nAry'
            , P\nAry
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\nAry(1)
            , 'nAry should be partially applied'
        );
        $this->assertEquals(
            1
            , P\nAry(1)->__invoke($countArgs)->__invoke(1,2,3)
            , 'nAry Partially applied should still produce a wrapped function that eats arguments'
        );
        $this->assertInstanceOf(
            Closure
            , P\nAry(1, $countArgs)
            , 'nAry fully applied should produce a closure'
        );
        $this->assertEquals(
            1
            , P\nAry(1, $countArgs)->__invoke(1,2,3,4)
            , 'fully applied should still work the same as partially applied, eating arguments'
        );
    }

    public function test_unary()
    {
        $countArgs = function () {return func_num_args();};
        $this->assertStringEndsWith(
            '\unary'
            , P\unary
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\unary($countArgs)
            , 'unary should return a closure'
        );

        $this->assertEquals(
            1
            , P\unary($countArgs)->__invoke(1,2,3)
            , 'Unary should eat all but one argument'
        );
    }
}
