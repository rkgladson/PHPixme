<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/8/2016
 * Time: 12:46 PM
 */

namespace tests\PHPixme;
require_once "tests/PHPixme_TestCase.php";
use PHPixme as P;


class MaybeTest extends PHPixme_TestCase
{

    public function test_maybe_companion() {
        $this->assertStringEndsWith(
            '\Maybe'
            , P\Maybe
            , 'Ensure the constant ends with the function name'
        );
    }
    public function maybeEmptyProvider()
    {
        return [
            [[]]
            , [null]
        ];
    }

    public function maybeSomethingProvider()
    {
        return [
            [0]
            , ['']
            , [P\None()]
            , [false]
            , [true]
            , [1]
            , [1.1]
            , ['1']
            , [[1]]
            , [new \stdClass()]
            , [P\Some('')]
        ];
    }


    /**
     * @dataProvider maybeEmptyProvider
     */
    public function test_Maybe_companion_none_result($value)
    {

        $this->assertInstanceOf(
            P\None
            , P\Maybe($value)
            , 'Value ' . json_encode($value, true) . ' should be of type None'
        );
    }

    /**
     * @dataProvider maybeSomethingProvider
     */
    public function test_Maybe_companion_some_result($value)
    {
        $this->assertInstanceOf(
            P\Some
            , P\Maybe($value)
            , 'Value ' . json_encode($value, true) . ' should be of type Some'
        );
    }

    /**
     * @dataProvider maybeEmptyProvider
     */
    public function test_static_of_nothing($value)
    {
        $this->assertInstanceOf(
            P\None
            , P\Maybe::of($value)
            , 'Value ' . json_encode($value, true) . ' should be of type Null'
        );
    }

    /**
     * @dataProvider maybeSomethingProvider
     */
    public function test_static_of_something($value)
    {

        $this->assertInstanceOf(
            P\Some
            , P\Maybe::of($value)
            , 'Value ' . json_encode($value, true) . ' should be of type Some'
        );
    }

    /**
     * @dataProvider maybeEmptyProvider
     */
    public function test_static_from_nothing($value)
    {

        $this->assertInstanceOf(
            P\None
            , P\Maybe::from($value)
            , 'Value ' . json_encode($value, true) . ' should be of type Null'
        );

    }

    /**
     * @dataProvider maybeSomethingProvider
     */
    public function test_static_from_something($value)
    {
        $this->assertInstanceOf(
            P\Some
            , P\Maybe::from($value)
            , 'Value ' . json_encode($value, true) . ' should be of type Some'
        );
    }
}
