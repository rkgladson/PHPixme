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

    public function test_combine()
    {
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

        $array = [1, 2, 3];
        $this->assertEquals(
            json_encode(array_reverse($array))
            , P\combine('json_encode')->__invoke('array_reverse')->__invoke($array)
            , 'combine should be able to chain the outputs to produce hof results'
        );
    }

    public function test_K_estrel($value = true, $notValue = false)
    {
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

    public function test_KI_te($value = true, $notValue = false)
    {
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

    public function test_I_diot_bird($value = true)
    {
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

    public function test_S_tarling($value = true)
    {
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

    public function test_S_tarling_scenario_tupleMaker($array = [1, 2, 3, 4])
    {
        // Test to see if we can fix array_map through starling to get the key with the value
        $kvTupple = function ($v, $k) {
            return [$k, $v];
        };
        $kvMap = P\ternary('array_map')->__invoke($kvTupple);
        $this->assertEquals(
            array_map($kvTupple, $array, array_keys($array))
            , P\S($kvMap, 'array_keys')->__invoke($array)
        );
    }


    public function foldCallbackProvider()
    {
        return [
            'array callback' => [
                [1], 1, 0
            ]
            , 'traversable callback' => [
                new \ArrayIterator([1]), 1, 0
            ]
            , 'natural interface callback' => [
                P\Seq([1]), 1, 0
            ]
        ];
    }

    /**
     * @dataProvider foldCallbackProvider
     */
    public function test_fold_callback($value, $expVal, $expKey)
    {
        $startVal = 1;
        P\fold(function () use ($startVal, $value, $expVal, $expKey) {
            $this->assertEquals(
                4
                , func_num_args()
                , 'fold callback should receive four arguments'
            );
            $this->assertEquals(
                $startVal
                , func_get_arg(0)
                , 'fold callback $prevVal should equal startValue'
            );
            $this->assertEquals(
                $expVal
                , func_get_arg(1)
                , 'fold callback $value should equal to expected value'
            );
            $this->assertEquals(
                $expKey
                , func_get_arg(2)
                , 'fold callback $key should equal to expected key'
            );
            if (is_object($value)) {
                $this->assertTrue(
                    $value === func_get_arg(3)
                    , 'fold callback $container should be the same instance as the object'
                );
            } else {
                $this->assertEquals(
                    $value
                    , func_get_arg(3)
                    , 'fold callback $container should equal to the array'
                );
            }

            return func_get_arg(0);
        }, $startVal, $value);
    }

    public function test_fold($value = 1, $array = [1, 2, 3, 4])
    {
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
        $this->assertEquals(
            $array[count($array) - 1]
            , P\fold(P\flip(P\I), $value, $array)
            , 'The flipped idiot applied to reduce should always return the last unless empty'
        );
    }

    public function foldScenarioProvider()
    {
        $add = function ($a, $b) {
            return $a + $b;
        };
        return [
            'add simple empty array' => [
                []
                , 0
                , $add
                , 0
            ]
            , 'add simple S[]' => [
                P\Seq::of()
                , 0
                , $add
                , 0
            ]
            , 'add simple None' => [
                P\None()
                , 0
                , $add
                , 0
            ]
            , 'ArrayObject[]' => [
                new \ArrayIterator([])
                , 0
                , $add
                , 0
            ]
            , 'add 1+2+3' => [
                [1, 2, 3]
                , 0
                , $add
                , 6
            ]
            , 'add S[1,2,3]' => [
                P\Seq::of(1, 2, 3)
                , 0
                , $add
                , 6
            ]
            , 'Some(2)+2' => [
                P\Some(2)
                , 2
                , $add
                , 4
            ]
            , 'add ArrayObject[1,2,3]' => [
                new \ArrayIterator([1, 2, 3])
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


    public function test_reduce($array = [1, 2, 3, 4])
    {
        $this->assertStringEndsWith(
            '\reduce'
            , P\reduce
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\reduce(P\I)
            , 'reduce when partially applied should return a closure'
        );
        $this->assertEquals(
            $array[0]
            , P\reduce(P\I)->__invoke($array)
            , 'An idiot applied to fold should always return the start value'
        );
        $this->assertEquals(
            $array[count($array) - 1]
            , P\reduce(P\flip(P\I), $array)
            , 'The flipped idiot applied to reduce should always return the last'
        );
    }

    public function reduceUndefinedBehaviorProvider()
    {
        return [
            '[]' => [[]]
            , 'None' => [P\None()]
            , 'S[]' => [P\Seq::of()]
            , 'ArrayItterator[]' => [new \ArrayIterator([])]
        ];
    }

    /**
     * @dataProvider reduceUndefinedBehaviorProvider
     * @expectedException \Exception
     */
    public function test_reduce_contract_violation($arrayLike = [])
    {
        P\reduce(P\I, $arrayLike);
    }

    public function reduceCallbackProvider()
    {
        return [
            'array callback' => [
                [1, 2], 1, 2, 1
            ]
            , 'traversable callback' => [
                new \ArrayIterator([1, 2]), 1, 2, 1
            ]
            , 'natural interface callback' => [
                P\Seq::of(1, 2), 1, 2, 1
            ]
        ];
    }

    /**
     * @dataProvider reduceCallbackProvider
     */
    public function test_reduce_callback($arrayLike, $firstVal, $expVal, $expKey)
    {
        P\reduce(function () use ($firstVal, $expVal, $expKey, $arrayLike) {
            $this->assertEquals(
                4
                , func_num_args()
                , 'reduce callback should receive four arguments'
            );
            $this->assertEquals(
                $firstVal
                , func_get_arg(0)
                , 'reduce callback $prevVal should equal startValue'
            );
            $this->assertEquals(
                $expVal
                , func_get_arg(1)
                , 'reduce callback should equal to expected value'
            );
            $this->assertEquals(
                $expKey
                , func_get_arg(2)
                , 'reduce callback $key should equal to expected key'
            );
            if (is_object($arrayLike)) {
                $this->assertTrue(
                    $arrayLike === func_get_arg(3)
                    , 'reduce callback $container should be the same instance as the source data'
                );
            } else {
                $this->assertEquals(
                    $arrayLike
                    , func_get_arg(3)
                    , '$container should equal to the array being reduced'
                );
            }

            return func_get_arg(0);
        }, $arrayLike);
    }

    public function reduceScenarioProvider()
    {
        $add = function ($a, $b) {
            return $a + $b;
        };
        return [
            'add 1' => [
                [1]
                , $add
                , 1
            ]
            , 'add S[1]' => [
                P\Seq::of(1)
                , $add
                , 1
            ]

            , 'add ArrayObject[1]' => [
                new \ArrayIterator([1])
                , $add
                , 1
            ]
            , 'add Some(2)' => [
                P\Some(2)
                , $add
                , 2
            ]
            , 'add 1+2+3' => [
                [1, 2, 3]
                , $add
                , 6
            ]
            , 'add S[1,2,3]' => [
                P\Seq::of(1, 2, 3)
                , $add
                , 6
            ]

            , 'add ArrayObject[1,2,3]' => [
                new \ArrayIterator([1, 2, 3])
                , $add
                , 6
            ]
        ];
    }

    /**
     * @dataProvider reduceScenarioProvider
     */
    public function test_reduce_scenario($arrayLike, $action, $expected)
    {
        $this->assertEquals(
            $expected
            , P\reduce($action, $arrayLike)
        );
    }

    public function test_map($array = [1, 2, 3])
    {
        $this->assertStringEndsWith(
            '\map'
            , P\map
            , 'Ensure the constant is assigned to its function name'
        );
        $this->assertInstanceOf(
            Closure
            , P\map(P\I)
            , 'map when partially applied should return a closure'
        );
        $result = P\map(P\I)->__invoke($array);
        $this->assertEquals(
            $array
            , $result
            , 'map applied with idiot should produce a functionally identical array'
        );
        $result[0] += 1;
        $this->assertNotEquals(
            $array
            , $result
            , 'map applied with idiot should not actually be the same instance of array'
        );
    }

    public function mapCallbackProvider()
    {
        return [
            '[1]' => [
                [1], 1, 0
            ]
            , 'S[1]' => [
                P\Seq::of(1), 1, 0
            ]
            , 'Some(1)' => [
                P\Some::of(1), 1, 0
            ]
            , 'ArrayItterator[1]' => [
                new \ArrayIterator([1]), 1, 0
            ]
        ];
    }

    /**
     * @dataProvider mapCallbackProvider
     */
    public function test_map_callback($arrayLike, $expVal, $expKey)
    {
        P\map(function () use ($arrayLike, $expVal, $expKey) {
            $this->assertTrue(
                3 === func_num_args()
                , 'map callback should receive three arguments'
            );
            $this->assertEquals(
                $expVal
                , func_get_arg(0)
                , 'map callback $value should be equal to the value expected'
            );
            $this->assertEquals(
                $expKey
                , func_get_arg(1)
                , 'map callback $key should be defined'
            );
            if (is_object($arrayLike)) {
                $this->assertTrue(
                    $arrayLike === func_get_arg(2)
                    , 'map callback $container should be the same instance as the source data being mapped'
                );
            } else {
                $this->assertEquals(
                    $arrayLike
                    , func_get_arg(2)
                    , 'map callback $container should equal to the array being mapped'
                );
            }
        }, $arrayLike);
    }

    public function mapScenarioProvider()
    {
        $x2 = function ($value) {
            return $value * 2;
        };
        return [
            '[1,2] * 2' => [
                [1, 2]
                , $x2
                , [2, 4]
            ]
            , 'ArrayIterator[1,2] * 2' => [
                new \ArrayIterator([1, 2])
                , $x2
                , [2, 4]
            ]
            , 'S[1,2] * 2' => [
                P\Seq::of(1, 2)
                , $x2
                , P\Seq::of(2, 4)
            ]
            , 'Some(1) *2' => [
                P\Some(1)
                , $x2
                , P\Some(2)
            ]
            , 'None * 2' => [
                P\None()
                , $x2
                , P\None()
            ]
            , '[1,2,3] to string' => [
                [1, 2, 3]
                , function ($value) {
                    return "$value";
                }
                , ['1', '2', '3']
            ]
        ];
    }

    /**
     * @dataProvider mapScenarioProvider
     */
    public function test_map_scenario($arrayLike, $hof, $expected)
    {
        $this->assertEquals($expected
            , P\map($hof, $arrayLike)
            , 'map on array like should have the expected resultant'
        );
    }
}