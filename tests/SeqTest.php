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
                , 'Seq->fold callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->fold callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->fold callback $container should be itself'
            );
            return $prevValue;
        });
    }

    public function foldAdditionProvider()
    {
        return [
            'empty' => [P\Seq::from([]), 0]
            , 'from 1 to 9' => [P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
        ];
    }

    /**
     * @dataProvider foldAdditionProvider
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

    public function reduceAdditionProvider()
    {
        return [
            'only zero' => [P\Seq::of(0), 0]
            , 'from 1 to 9' => [P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 45]
        ];
    }

    /**
     * @dataProvider reduceAdditionProvider
     * @requires test_magic_invoke
     * @requires test_head
     */
    public function test_reduce_callback($seq)
    {
        $head = $seq->head();
        $seq->reduce(function () use ($seq, $head) {
            $this->assertTrue(
                4 === func_num_args()
                , 'Seq->reduce callback should receive four arguments'
            );

            $prevValue = func_get_arg(0);
            $value = func_get_arg(1);
            $key = func_get_arg(2);
            $container = func_get_arg(3);

            $this->assertTrue(
                $prevValue === $head
                , 'Seq->reduce callback $prevValue should be the first value in the Seq'
            );
            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->reduce callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->reduce callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->reduce callback $container should be itself'
            );
            return $prevValue;
        });
    }

    /**
     * Ensure that the contract is maintained that reduce on none is undefined behavior
     * @expectedException \Exception
     */
    public function test_reduce_contract_broken()
    {
        P\Seq::of()->reduce(function () {
            return true;
        });
    }

    /**
     * @dataProvider reduceAdditionProvider
     */
    public function test_reduce_scenario_add($seq, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->reduce(function ($a, $b) {
            return $a + $b;
        })
            , 'Seq->reduce application of add should produced the expected result'
        );
    }

    public function unionDataProvider()
    {
        return [
            'S[] with Some(1) and []' => [
                P\Seq::of()
                , [[], P\Some::of(1)]
                , P\Seq::of(1)]
            , 'S[1,2,3] with [4], S[5,6], and None' => [
                P\Seq::of(1, 2, 3)
                , [[4], P\Seq::of(5, 6), P\None()]
                , P\Seq::of(1, 2, 3, 4, 5, 6)
            ]
            , 'S[None, Some(1)] with Some(1)' => [
                P\Seq::of(P\None, P\Some(1))
                , [P\None(), P\Some(2)]
                , P\Seq::of(P\None, P\Some(1), 2)
            ]
        ];

    }

    /**
     * @dataProvider unionDataProvider
     */
    public function test_union($seq, $arrayLikeN, $expected)
    {
        $this->assertEquals(
            $expected
            , call_user_func_array([$seq, 'union'], $arrayLikeN)
            , 'Seq->union is expected to join the data with itself and the passed array likes'
        );
    }

    public function findProvider()
    {
        return [
            'find 1' => [
                P\Seq::of(1, 2, 3)
                , 1
                , P\Some(1)
            ]
            , 'fail to find 4' => [
                P\Seq::of(1, 2, 3)
                , 4
                , P\None()
            ]
        ];
    }

    /**
     * @dataProvider findProvider
     * @requires test_magic_invoke
     */
    public function test_find_callback($seq)
    {
        $seq->find(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->find callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->find callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->find callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->find callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider findProvider
     */
    public function test_find($seq, $value, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->find(function ($x) use ($value) {
            return $x === $value;
        })
            , 'Seq->find should result in the expected value for any positive otucome of callback'
        );
    }

    public function walkProvider()
    {
        return [
            'from 1 to 9' => [
                P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9), 9
            ]
            , 'Nothing' => [
                P\Seq::of(), 0
            ]
        ];
    }

    /**
     * @dataProvider walkProvider
     */
    public function test_walk_callback($seq)
    {
        $seq->walk(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->walk callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->walk callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->walk callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->walk callback $container should be itself'
            );
        });
    }

    /**
     * @dataProvider walkProvider
     */
    public function test_walk($seq, $length)
    {
        $ran = 0;
        $seq->walk(function () use (&$ran) {
            $ran += 1;
        });
        $this->assertEquals(
            $length
            , $ran
            , 'Seq->walk should of ran the length of the sequence'
        );
    }


    public function headProvider()
    {
        return [
            'keyless' => [
                P\Seq::of(1, 2, 3)
                , 1
            ]
            , 'keyed' => [
                P\Seq::from([
                    'one' => 1
                    , 'two' => 2
                    , 'three' => 3
                ])
                , 1
            ]
            , 'empty' => [
                P\Seq::of()
                , null
            ]
        ];
    }

    /**
     * @dataProvider headProvider
     */
    public function test_head($seq, $expects)
    {
        $this->assertEquals(
            $expects
            , $seq->head()
            , 'Seq->head should return the head element'
        );
    }


    public function tailProvider()
    {
        return [
            'keyless' => [
                P\Seq::of(1, 2, 3)
                , P\Seq::of(2, 3)
            ]
            , 'keyed' => [
                P\Seq::from([
                    'one' => 1
                    , 'two' => 2
                    , 'three' => 3
                ])
                , P\Seq::from([
                    'two' => 2
                    , 'three' => 3
                ])
            ]
            , 'empty' => [
                P\Seq::of()
                , P\Seq::of()
            ]
        ];
    }

    /**
     * @dataProvider tailProvider
     */
    public function test_tail($seq, $expects)
    {
        $this->assertEquals(
            $expects
            , $seq->tail()
            , 'Seq->head should return the rest of the Sequence'
        );
    }

    public function indexOfProvider()
    {
        $none = P\None;
        $some1 = P\Some(1);
        $one = 1;
        return [
            'keyed source find None S[one=>1, none=>None, some=>Some(1) ]' => [
                P\Seq::from(['one' => $one, 'none' => $none, 'some' => $some1])
                , $none
                , 'none'
            ]
            , 'source find None S[1,None, Some(1)]' => [
                P\Seq::of($one, $none, $some1)
                , $none
                , 1
            ]
            , 'source find Some(1) in S[1,2,Some(1),3]' => [
                P\Seq::of(1, 2, $some1, 3)
                , $some1
                , 2
            ]
            , 'fail to find Some(1) in S[1,2,3]' => [
                P\Seq::of(1, 2, 3)
                , $some1
                , -1
            ]
            , 'fail to find Some(1) in S[]' => [
                P\Seq::of()
                , $some1
                , -1
            ]
        ];
    }

    /**
     * @dataProvider indexOfProvider
     */
    public function test_indexOf($haystack, $needle, $expected)
    {
        $this->assertEquals(
            $expected
            , $haystack->indexOf($needle)
            , 'Seq->indexOf should yield the expected results for $needle in $haystack'
        );
    }

    public function partitionProvider()
    {
        return [
            'from 1 to 9 paritioned by odd (true) and even(false)' => [
                P\Seq::of(1, 2, 3, 4, 5, 6, 7, 8, 9)
                , function ($value, $key) {
                    return ($key % 2) === 0;
                }
                , P\Seq::of(
                    P\Seq::from([1 => 2, 3 => 4, 5 => 6, 7 => 8])
                    , P\Seq::From([0 => 1, 2 => 3, 4 => 5, 6 => 7, 8 => 9])
                )
            ]
        ];
    }

    /**
     * @dataProvider partitionProvider
     */
    public function test_partition_callback($seq)
    {
        $seq->partition(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->partition callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->partition callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->partition callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->partition callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider partitionProvider
     */
    public function test_partition($seq, $hof, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->partition($hof)
            , 'Seq->partition should separate as expected the results of the $hof based on its true(index 1) false(index 0) value'
        );
    }

    public function groupProvider()
    {
        return [
            '' => [
                P\Seq::of(1, '2', 3, P\Some(4), 5, '6', 7)
                , function ($value) {
                    if (is_string($value)) {
                        return 'string';
                    }
                    if (is_numeric($value)) {
                        return 'number';
                    }
                    if (is_object($value)) {
                        return 'object';
                    }
                    return 'donno';
                }
                , P\Seq::from([
                    'number' => P\Seq::from([0 => 1, 2 => 3, 4 => 5, 6 => 7])
                    , 'string' => P\Seq::from([1 => '2', 5 => 6])
                    , 'object' => P\Seq::from([3 => P\Some(4)])
                ])
            ]
        ];
    }

    /**
     * @dataProvider groupProvider
     */
    public function test_group_callback($seq)
    {
        $seq->group(function () use ($seq) {
            $this->assertTrue(
                3 === func_num_args()
                , 'Seq->group callback should receive three arguments'
            );
            $value = func_get_arg(0);
            $key = func_get_arg(1);
            $container = func_get_arg(2);

            $this->assertTrue(
                ($seq($key)) === $value
                , 'Seq->group callback $value should be equal to the value at $key'
            );
            $this->assertNotFalse(
                $key
                , 'Seq->group callback $key should be defined'
            );
            $this->assertTrue(
                $seq === $container
                , 'Seq->group callback $container should be itself'
            );
            return true;
        });
    }

    /**
     * @dataProvider groupProvider
     */
    public function test_group($seq, $hof, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->group($hof)
            , 'Seq->group, applied to the key values given by the hof, should be a nested sequence of expected Seq'
        );
    }

    public function dropProvider()
    {
        return [
            'empty drop 5' => [
                P\Seq::of()
                , 5
                , P\Seq::of()
            ]
            , 'S[1,2,3,4] drop 3' => [
                P\Seq::of(1, 2, 3, 4)
                , 3
                , P\Seq::from([3 => 4])
            ]
        ];
    }

    /**
     * @dataProvider dropProvider
     */
    public function test_drop($seq, $number, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->drop($number)
            , 'Seq->drop of amount results are functionally equivilent to expected'
        );
    }

    public function dropRightProvider()
    {
        return [
            'empty drop right 5' => [
                P\Seq::of()
                , 5
                , P\Seq::of()
            ]
            , 'S[1,2,3,4] drop right 3' => [
                P\Seq::of(1, 2, 3, 4)
                , 3
                , P\Seq::from([0 => 1])
            ]
        ];
    }

    /**
     * @dataProvider dropRightProvider
     */
    public function test_dropRight($seq, $amount, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->dropRight($amount)
            , 'Seq->dropRight of amount should produce the Sequence expected'
        );
    }

    public function takeProvider()
    {
        return [
            'S[]->takeRight(5)' => [
                P\Seq::of(), 5, P\Seq::of()
            ]
            , 'S[1,2,3,4,5,6]->takeRight(2)' => [
                P\Seq::of(1, 2, 3, 4, 5, 6), 2, P\Seq::from([0 => 1, 1 => 2])
            ]
        ];
    }

    /**
     * @dataProvider takeProvider
     */
    public function test_take($seq, $amount, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->take($amount)
            , 'Seq->take of amount should yield expected'
        );
    }

    public function takeRightProvider()
    {
        return [
            'S[]->takeRight(5)' => [
                P\Seq::of(), 5, P\Seq::of()
            ]
            , 'S[1,2,3,4,5,6]->takeRight(2)' => [
                P\Seq::of(1, 2, 3, 4, 5, 6), 2, P\Seq::from([4 => 5, 5 => 6])
            ]
        ];
    }

    /**
     * @dataProvider takeRightProvider
     */
    public function test_takeRight($seq, $amount, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->takeRight($amount)
            , 'Seq->takeRight of amount should yield expected'
        );
    }

    public function isEmptyProvider()
    {
        return [
            'nothing' => [
                []
            ],
            'from 1 to 9' => [
                [1, 2, 3, 4, 5, 6, 7, 8, 9]
            ]
        ];
    }

    /**
     * @dataProvider isEmptyProvider
     */
    public function test_isEmpty($source)
    {
        $this->assertEquals(
            empty($source)
            , P\Seq::from($source)->isEmpty()
            , 'Seq->isEmpty should be true if the source was empty'
        );
    }


    public function toStringProvider()
    {
        return [
            'empty' => [
                [], ''
            ]
            , 'S{integer}' => [
                [1, 2, 3, 4, 5]
                , '!'
            ]
            , 'S{string}' => [
                ['a', 'b', 'c', 'd']
                , ';'
            ]
            , 'Keyed S{integer}' => [
                ['one' => 1, 'two' => 2]
                , ', '
            ]
        ];
    }

    /**
     * @dataProvider toStringProvider
     */
    public function test_toString($array, $glue)
    {
        $this->assertEquals(
            implode($glue, $array)
            , P\Seq::from($array)->toString($glue)
        );
    }

    public function toJsonProvider()
    {
        return [
            'empty' => [
                []
            ]
            , 'S{integer}' => [
                [1, 2, 3, 4, 5]
            ]
            , 'S{string}' => [
                ['a', 'b', 'c', 'd']
            ]
            , 'Keyed S{integer}' => [
                ['one' => 1, 'two' => 2]
            ]
        ];
    }

    /**
     * @dataProvider toJsonProvider
     */
    public function test_toJson($array)
    {
        $this->assertEquals(
            json_encode($array)
            , P\Seq::from($array)->toJson()
            , 'Seq->toJson should be functionally equivalent to json_encode(Seq->toArray)'
        );
    }

    public function reverseProvider()
    {
        return [
            'S[1,2,3]' => [
                P\Seq::of(1, 2, 3)
                , P\Seq::from([2 => 3, 1 => 2, 0 => 1])
            ]
        ];
    }

    /**
     * @dataProvider reverseProvider
     */
    public function test_reverse($seq, $expected)
    {
        $this->assertEquals(
            $expected
            , $seq->reverse()
            , 'Seq->reverse should reverse the traversal order of a Seq'
        );
    }
}
