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
}
