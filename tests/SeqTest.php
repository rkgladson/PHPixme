<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/11/2016
 * Time: 3:38 PM
 */

namespace tests\PHPixme;
require_once "tests/PHPixme_TestCase.php";
use PHPixme as P;

class SeqTest extends PHPixme_TestCase
{
    public function seqSourceProvider()
    {
        return [
            [[]]
            , [[1, 2, 3]]
            , [P\Some(1)]
            , [P\None()]
            , [['one' => 1, 'two' => 2]]
            , [P\Seq([])]
        ];
    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_seq_companion($value)
    {
        $seq = P\Seq($value);
        $this->assertInstanceOf(
            P\Seq
            , $seq
            , 'Seq companion function should produce instances of Seq class'
        );
    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_seq_static_of($value)
    {

        $seq = call_user_func_array(P\Seq . '::of', is_array($value) ? $value : [$value]);
        $this->assertInstanceOf(
            P\Seq
            , $seq
            , 'Seq::of should produce a instance of Seq class'
        );
    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_static_from($value)
    {
        $seq = P\Seq::from($value);
        $this->assertInstanceOf(
            P\Seq
            , $seq
            , 'Seq::from should produce an instance of Seq Class'
        );
    }

    public function test_toArray()
    {
        // The only meaningful way to test this is with array only sources
        $values = [
            []
            , [1, 2, 3]
            , ['one' => 1, 'two' => 2]
            , [P\Some(1), P\None()]
            , [P\Seq::of(1, 2, 3), P\Seq::of(4, 5, 6)]
        ];
        foreach ($values as $value) {
            $seq = P\Seq($value);
            $this->assertEquals(
                $value
                , $seq->toArray()
                , 'Seq->toArray will should return its inner array, and should be functionally equivalent to the array it was given'
            );
        }

    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_magic_invoke($value)
    {
        $seq = P\Seq($value);

        foreach ($value as $k => $v) {
            $this->assertTrue(
                $seq($k) === $v
                , 'Seq->__invoke should accept a key and return its value at the key'
            );
        }
    }

    /**
     * @dataProvider seqSourceProvider
     * @requires test_magic_invoke
     */
    public function test_map_callback($value)
    {
        $seq = P\Seq($value);
        $seq->map(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->map callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->map callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->map callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->map callback $container should be itself'
            );
        });
    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_map_scenario_identity($value)
    {
        $id = function ($x) {
            return $x;
        };
        $seq = P\Seq($value);
        $result = $seq->map($id);
        $this->assertFalse(
            $seq === $result
            , 'Seq->map should not return the same instance'
        );
        $this->assertEquals(
            $result
            , $seq
            , 'Seq->map applied with id should be functionally equivalent'
        );
    }


    /**
     * @dataProvider seqSourceProvider
     * @requires test_magic_invoke
     */
    public function test_filter_callback($value)
    {
        $seq = P\Seq($value);
        $seq->filter(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->filter callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->filter callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->filter callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->filter callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_filter($value)
    {
        $seq = P\Seq($value);
        $tResult = $seq->filter(function () {
            return true;
        });
        $this->assertFalse(
            $tResult === $seq
            , 'Seq->filter callback true is not an identity'
        );
        $this->assertEquals(
            $seq
            , $tResult
            , 'Seq->filter callback true still contains the same data'
        );

        $fResult = $seq->filter(function () {
            return false;
        });
        $this->assertEquals(
            P\Seq([])
            , $fResult
            , 'Seq-filter callback false should contain no data'
        );
    }

    /**
     * @dataProvider seqSourceProvider
     * @requires test_magic_invoke
     */
    function test_filterNot_callback($value)
    {
        $seq = P\Seq($value);
        $seq->filter(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->filterNot callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->filterNot callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->filterNot callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->filterNot callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider seqSourceProvider
     */
    public function test_filterNot($value)
    {
        $seq = P\Seq($value);
        $tResult = $seq->filterNot(function () {
            return false;
        });
        $this->assertFalse(
            $tResult === $seq
            , 'Seq->filterNot callback false is not an identity'
        );
        $this->assertEquals(
            $seq
            , $tResult
            , 'Seq->filterNot callback false still contains the same data'
        );

        $fResult = $seq->filterNot(function () {
            return true;
        });
        $this->assertEquals(
            P\Seq([])
            , $fResult
            , 'Seq-filterNot callback true should contain no data'
        );
    }

    public function nestedTestProvider()
    {
        // Provides flatten operations with the solution
        return [
            'nested array' => [
                [[1, 2, 3], [4, 5, 6]]
                , [1, 2, 3, 4, 5, 6]
            ]
            , 'array with some' => [
                [P\Some(1), P\Some(2), P\Some(3)]
                , [1, 2, 3]
            ]
            , 'Seq of Seq' => [
                P\Seq::of(P\Seq::of(1, 2, 3), P\Seq::of(4, 5, 6))
                , [1, 2, 3, 4, 5, 6]
            ]
            , 'Seq of array' => [
                P\seq::of([1, 2, 3], [4, 5, 6])
                , [1, 2, 3, 4, 5, 6]
            ]
        ];
    }


    /**
     * @dataProvider nestedTestProvider
     */
    public function test_flatMap_callback($value, $solution)
    {
        $seq = P\Seq($value);
        $seq->flatMap(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->flatMap callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->flatMap callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->flatMap callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->flatMap callback $container should be itself'
            );
            return $value;
        });
    }

    /**
     * Ensure the function throws an exception when the contract of a non-traversable item is passed to it from the $hof
     * @expectedException \Exception
     */
    public function test_flatMap_contract_broken()
    {
        P\Seq::of(1, 2, 3)->flatMap(function () {
            return true;
        });
    }

    /**
     * @dataProvider nestedTestProvider
     * @depends      test_toArray
     */
    public function test_flatMap_scenario_idenity($input, $expected)
    {
        $id = function ($value) {
            return $value;
        };
        $this->assertEquals(
            $expected
            , P\Seq::from($input)->flatMap($id)->toArray()
            , 'Seq->flatMap applied with id should be functionally equivalent its merged array'
        );
    }

    /**
     * @dataProvider nestedTestProvider
     * @depends      test_toArray
     */
    public function test_flatten($input, $expected)
    {
        $this->assertEquals(
            $expected
            , P\Seq::from($input)->flatten()->toArray()
            , 'Seq->flatten should return a sequence that is functionally equivalent to a merged array'
        );
    }

    /**
     * Ensure the function throws an exception when the contract of a non-traversable item is tried to be merged
     * @expectedException \Exception
     */
    public function test_flatten_contract_broken()
    {
        P\Seq::of(1, 2, 3)->flatten();
    }

    /**
     * @dataProvider seqSourceProvider
     * @requires test_magic_invoke
     */
    public function test_fold_callback($value)
    {
        $seq = P\Seq($value);
        $seq->fold(0, function () use ($seq) {
            $this->assertTrue(
                4 === func_num_args()
                , 'Seq->fold callback should receive four arguments'
            );

            $prevValue = func_get_arg(0);
            $value = func_get_arg(1);
            $key = func_get_arg(2);
            $container = func_get_arg(3);

            $this->assertTrue(
                $prevValue === 0
                , 'Seq->fold callback $prevValue should be its start value'
            );
            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->map callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->map callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->map callback $container should be itself'
            );
            return $prevValue;
        });
    }

    public function additionProvider()
    {
        return [
            'empty' => [P\Seq::from([]), 0]
            , 'from 1 to 9' => [P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
        ];
    }

    /**
     * @dataProvider additionProvider
     */
    public function test_fold_scenario_addition($seq, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->fold(0, function ($a, $b) {
            return $a + $b;
        })
            , 'Seq->fold applied to addition should produce the sum of the sequence'
        );
    }


    public function forAllProvider()
    {
        return [
            'seq from 1 to 4' => [P\Seq::of(1, 2, 3, 4), true]
            , 'seq from -2 to 2' => [P\Seq::of(-2, -1, 0, 1, 2), false]
            , 'seq from -4 to -1' => [P\Seq::of(-4, -3, -2, -1), false]
        ];
    }

    /**
     * @dataProvider forAllProvider
     * @requires test_magic_invoke
     */
    public function test_forAll_callback($seq)
    {
        $seq->forAll(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->forAll callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->forAll callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->forAll callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->forAll callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider forAllProvider
     */
    public function test_forAll_scenario_positive($seq, $expected)
    {
        $positive = function ($value) {
            return $value > 0;
        };
        $this->assertEquals(
            $expected
            , $seq->forAll($positive)
            , 'Seq->forAll callback should all be as expected based on positive result'
        );
    }

    public function forNoneProvider()
    {
        return [
            'seq from 1 to 4' => [P\Seq::of(1, 2, 3, 4), false]
            , 'seq from -2 to 2' => [P\Seq::of(-2, -1, 0, 1, 2), false]
            , 'seq from -4 to -1' => [P\Seq::of(-4, -3, -2, -1), true]
        ];
    }

    /**
     * @dataProvider forNoneProvider
     * @requires test_magic_invoke
     */
    public function test_forNone_callback($seq)
    {
        $seq->forNone(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->forNone callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->forNone callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->forNone callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->forNone callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider forNoneProvider
     */
    public function test_forNone_scenario_positive($seq, $expected)
    {
        $positive = function ($value) {
            return $value > 0;
        };
        $this->assertEquals(
            $expected
            , $seq->forNone($positive)
            , 'Seq->forNone callback should have none be as expected based on positive result'
        );
    }

    public function forSomeProvider()
    {
        return [
            'seq from 1 to 4' => [P\Seq::of(1, 2, 3, 4), true]
            , 'seq from -2 to 2' => [P\Seq::of(-2, -1, 0, 1, 2), true]
            , 'seq from -4 to -1' => [P\Seq::of(-4, -3, -2, -1), false]
        ];
    }

    /**
     * @dataProvider forSomeProvider
     * @requires test_magic_invoke
     */
    public function test_forSome_callback($seq)
    {
        $seq->forSome(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->forSome callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->forSome callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->forSome callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->forSome callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider forSomeProvider
     */
    public function test_forSome_scenario_positive($seq, $expected)
    {
        $positive = function ($value) {
            return $value > 0;
        };
        $this->assertEquals(
            $expected
            , $seq->forSome($positive)
            , 'Seq->forNone callback should at least one be as expected based on positive result'
        );
    }

}
