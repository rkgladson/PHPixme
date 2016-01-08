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
    public function test_Maybe_companion()
    {
        $empty = [[], null];
        foreach ($empty as $value) {
            $this->assertInstanceOf(
                P\None
                , P\Maybe($value)
                , 'Value ' . json_encode($value, true) . ' should be of type Null'
            );
        }
        $values = [0, '', P\None, false, true, 1, '1'];
        foreach ($values as $value) {
            $this->assertInstanceOf(
                P\Some
                , P\Maybe($value)
                , 'Value ' . json_encode($value, true) . ' should be of type Some'
            );
        }

    }

    public function test_static_of() {
        $empty = [[], null];
        foreach ($empty as $value) {
            $this->assertInstanceOf(
                P\None
                , P\Maybe::of($value)
                , 'Value ' . json_encode($value, true) . ' should be of type Null'
            );
        }
        $values = [0, '', P\None, false, true, 1, '1'];
        foreach ($values as $value) {
            $this->assertInstanceOf(
                P\Some
                , P\Maybe::of($value)
                , 'Value ' . json_encode($value, true) . ' should be of type Some'
            );
        }
    }

    public function test_static_from() {
        $empty = [[], null];
        foreach ($empty as $value) {
            $this->assertInstanceOf(
                P\None
                , P\Maybe::from($value)
                , 'Value ' . json_encode($value, true) . ' should be of type Null'
            );
        }
        $values = [0, '', P\None, false, true, 1, '1'];
        foreach ($values as $value) {
            $this->assertInstanceOf(
                P\Some
                , P\Maybe::from($value)
                , 'Value ' . json_encode($value, true) . ' should be of type Some'
            );
        }
    }
}
