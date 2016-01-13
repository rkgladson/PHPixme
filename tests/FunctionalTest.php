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
    public function test_curry()
    {
        $countArgs = function () {
            return func_num_args();
        };
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
            , $param3(1)
            , 'Curried functions should be still a closure when partially applied'
        );
        $this->assertEquals(
            3
            , $param3(1, 2, 3)
            , 'Curried functions should run when the minimum arguments are applied'
        );
        $this->assertEquals(
            4
            , $param3(1, 2, 3, 4)
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
            , $curry2($countArgs)->__invoke(1, 2)
            , 'The partially applied version of curry should behave just like the non-partially applied one'
        );
    }

    public function test_nAry()
    {
        $countArgs = function () {
            return func_num_args();
        };
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
            , P\nAry(1)->__invoke($countArgs)->__invoke(1, 2, 3)
            , 'nAry Partially applied should still produce a wrapped function that eats arguments'
        );
        $this->assertInstanceOf(
            Closure
            , P\nAry(1, $countArgs)
            , 'nAry fully applied should produce a closure'
        );
        $this->assertEquals(
            1
            , P\nAry(1, $countArgs)->__invoke(1, 2, 3, 4)
            , 'fully applied should still work the same as partially applied, eating arguments'
        );
    }

    public function test_unary()
    {
        $countArgs = function () {
            return func_num_args();
        };
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
            , P\unary($countArgs)->__invoke(1, 2, 3)
            , 'Unary should eat all but one argument'
        );
    }

    public function test_binary()
    {
        $countArgs = function () {
            return func_num_args();
        };
        $this->assertStringEndsWith(
            '\binary'
            , P\binary
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\binary($countArgs)
            , 'binary should return a closure'
        );

        $this->assertEquals(
            2
            , P\binary($countArgs)->__invoke(1, 2, 3)
            , 'binary should eat all but two arguments'
        );
    }

    public function test_ternary()
    {
        $countArgs = function () {
            return func_num_args();
        };
        $this->assertStringEndsWith(
            '\ternary'
            , P\ternary
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\ternary($countArgs)
            , 'ternary should return a closure'
        );

        $this->assertEquals(
            3
            , P\ternary($countArgs)->__invoke(1, 2, 3, 4)
            , 'ternary should eat all but three arguments'
        );
    }

    public function test_nullary()
    {
        $countArgs = function () {
            return func_num_args();
        };
        $this->assertStringEndsWith(
            '\nullary'
            , P\nullary
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\nullary($countArgs)
            , 'nullary should return a closure'
        );

        $this->assertEquals(
            0
            , P\nullary($countArgs)->__invoke(1, 2, 3, 4)
            , 'nullary should eat all arguments'
        );
    }

    public function test_flip()
    {
        $getArgs = function () {
            return func_get_args();
        };
        $this->assertStringEndsWith(
            '\flip'
            , P\flip
            , 'Ensure the constant is assigned to its function name'
        );

        $this->assertInstanceOf(
            Closure
            , P\flip($getArgs)
            , 'Flip should return a closure'
        );

        $this->assertEquals(
            [2, 1, 3, 4, 5]
            , P\flip($getArgs)->__invoke(1, 2, 3, 4, 5)
            , 'Flip should flip the first two arguments'
        );
        $this->assertInstanceOf(
            Closure
            , P\flip($getArgs)->__invoke(1)
            , 'Flip partially applied should return a closure'
        );

        $this->assertEquals(
            [2, 1, 3, 4, 5]
            , P\flip($getArgs)->__invoke(1)->__invoke(2, 3, 4, 5)
            , 'Flip partially applied should return the flipped arguments'
        );

    }

    public function test_combine() {
        $this->assertStringEndsWith(
            '\combine'
            , P\combine
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\combine('json_encode', 'array_reverse')
            , 'combine should return a closure'
        );

        $this->assertInstanceOf(
            Closure
            , P\combine('json_encode')
            , 'combine should be a curried function'
        );

        $array = [1,2,3];
        $this->assertEquals(
            json_encode(array_reverse($array))
            , P\combine('json_encode')->__invoke('array_reverse')->__invoke($array)
            , 'combine should be able to chain the outputs to produce hof results'
        );
    }

    public function test_K_estrel ($value = true, $notValue = false) {
        $this->assertStringEndsWith(
            '\K'
            , P\K
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\K($value)
            , 'K should return a closure'
        );
        $this->assertEquals(
            $value
            , P\K($value)->__invoke($notValue)
            , 'K resultant closure should return the constant that has been closed'
        );
    }

    public function test_KI_te($value = true, $notValue = false) {
        $this->assertStringEndsWith(
            '\KI'
            , P\KI
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\KI($value)
            , 'KI should return a closure'
        );
        $this->assertEquals(
            $notValue
            , P\KI($value)->__invoke($notValue)
            , 'K resultant closure should ignore the constant and return the argument it recieves'
        );
    }

    public function test_I_diot_bird($value = true) {
        $this->assertStringEndsWith(
            '\I'
            , P\I
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertEquals(
            $value
            , P\I($value)
            , 'The notoriously simple idiot bird proves useful in unusual places'
        );
    }

    public function test_S_tarling($value = true) {
        $this->assertStringEndsWith(
            '\S'
            , P\S
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\S(P\I)
            , 'Starling should be able to be partially applied'
        );
        $this->assertEquals(
            $value
            , P\S(P\K)->__invoke(P\K($value))->__invoke($value)
            , 'S(K, K($value))($value) === $value is one of the more basic proofs to Starling'
        );

    }

    public function test_S_tarling_scenario_tupleMaker ($array = [1,2,3,4]) {
        // Test to see if we can fix array_map through starling to get the key with the value
        $kvTupple = function ($v, $k) {
            return [$k, $v];
        };
        $kvMap = P\ternary('array_map')->__invoke($kvTupple);
        $this->assertEquals(
            array_map($kvTupple, $array, array_keys($array))
            ,P\S($kvMap, 'array_keys')->__invoke($array)
        );
    }

    public function test_fold($value = 1, $array = [1,2,3,4]) {
        $this->assertStringEndsWith(
            '\fold'
            , P\fold
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\fold(P\I, $value)
            , 'fold when partially applied should return a closure'
        );
        $this->assertEquals(
            $value
            , P\fold(P\I, $value)->__invoke($array)
            , 'An idiot applied to fold should always return the start value'
        );
    }

    public function foldScenarioProvider() {
        $add = function($a, $b) { return $a + $b; };
        return [
            'add simple empty array'=>[
                []
                , 0
                , $add
                , 0
            ]
            , 'add simple S[]'=>[
                P\Seq::of()
                , 0
                , $add
                , 0
            ]
            , 'add simple None'=>[
                P\None()
                , 0
                , $add
                , 0
            ]
            , 'add empty ArrayObject' => [
                new \ArrayIterator([])
                , 0
                , $add
                , 0
            ]
            , 'add 1+2+3'=>[
                [1,2,3]
                , 0
                , $add
                , 6
            ]
            , 'add S[1,2,3]'=>[
                P\Seq::of()
                , 0
                , $add
                , 0
            ]
            , 'Some(2)+2'=>[
                P\Some(2)
                , 2
                , $add
                , 4
            ]
            , 'add ArrayObject[1,2,3]' => [
                new \ArrayIterator([1,2,3])
                , 0
                , $add
                , 6
            ]
        ];
    }

    /**
     * @dataProvider foldScenarioProvider
     */
    public function test_fold_scenario($arrayLike, $startVal, $action, $expected)
    {
        $this->assertEquals(
            $expected
            , P\fold($action, $startVal, $arrayLike)
        );
    }
}
