<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/8/2016
 * Time: 3:11 PM
 */
namespace tests\PHPixme;
require_once "tests/PHPixme_TestCase.php";
use PHPixme as P;

class SomeTest extends PHPixme_TestCase
{
    public function test_Some_companion($value = true)
    {
        $this->assertInstanceOf(
            P\Some,
            P\Some($value)
        );
    }

    public function test_Some_static_of($value = true)
    {
        $this->assertInstanceOf(
            P\Some,
            P\Some::of($value)
        );
    }

    public function test_Some_static_from($value = [true])
    {
        $this->assertInstanceOf(
            P\Some,
            P\Some::from($value)
        );
    }

    public function test_contains($value = true, $notValue = false)
    {
        $this->assertTrue(
            P\Some($value)->contains($value)
            , 'The some should contain itself'
        );
        $this->assertFalse(
            P\Some($value)->contains($notValue)
            , 'The Some should not contain anything other than itself'
        );
    }

    public function test_exists($value = true, $notValue = false)
    {
        $getValue = function ($x) use ($value) {
            return $x === $value;
        };
        $getNotValue = function ($x) use ($notValue) {
            return $x === $notValue;
        };
        $this->assertTrue(
            P\Some($value)->exists($getValue)
            , 'The some should contain itself'
        );
        $this->assertFalse(
            P\Some($value)->exists($getNotValue)
            , 'The Some should not contain anything other than itself'
        );
    }

    public function test_get($value = true)
    {
        $this->assertTrue(
            $value === (P\Some($value)->get())
            , 'The Some should be able to be unwrapped'
        );
    }

    public function test_getOrElse($value = true, $default = false)
    {
        $this->assertTrue(
            $value === (P\Some($value)->getOrElse($default))
            , 'The Some should be able to be unwrapped'
        );
    }

    public function test_isDefined($value = true)
    {
        $this->assertTrue(
            P\Some($value)->isDefined()
            , 'Some values are all defined.'
        );
    }

    public function test_orNull($value = true)
    {
        $contained = P\Some($value)->orNull();
        if (is_null($value)) {
            $this->assertNull(
                $contained
                , 'When Some contains null, it should equal null'
            );
        } else {
            $this->assertNotNull(
                $contained
                , 'This should never return its default of null'
            );
        }
    }

    public function test_orElse($value = true)
    {
        $some = P\Some($value);
        $this->assertTrue(
            $some === ($some->orElse(function () {
                throw new \Exception('This should never run');
            }))
            , 'orElse on Some is an identity'
        );
    }

    public function test_toSeq($value = true)
    {
        $this->assertInstanceOf(
            P\Seq
            , P\Some($value)->toSeq()
            , 'The Some container should be turned to a sequence'
        );
    }


    public function test_fold_callback($value = true, $startValue = null)
    {
        $some = P\Some($value);

        $output = $some->fold($startValue, function ($lastVal) use ($startValue, $value, $some) {
            $this->assertEquals(
                func_num_args()
                , 4
                , 'The signature of the fold callback is ($prevVal, $value, $key, $container)'
            );
            $this->assertTrue(
                func_get_arg(0) === $startValue
                , 'The $prevVal parameter should of been sent'
            );
            $this->assertTrue(
                func_get_arg(1) === $value
                , 'The $value parameter should be value'
            );
            $this->assertNotFalse(
                func_get_arg(2)
                , 'The key parameter should have been sent'
            );
            $this->assertTrue(
                func_get_arg(3) === $some
                , 'The container parameter should of been sent'
            );

            return $lastVal;
        });
        $this->assertTrue(
            $startValue === $output
            , 'The outcome should of been, in this case, what the start value was'
        );
    }

    public function test_fold_scenario_add($value = 1, $startVal = 1)
    {
        $add = function ($x, $y) {
            return $x + $y;
        };
        $this->assertEquals(
            $value + $startVal
            , P\Some($value)->fold($startVal, $add)
            , 'The fold should be able to preform a simple add on a single length item'
        );
    }

    public function test_reduce($value = true)
    {
        $this->assertEquals(
            $value
            , P\Some($value)->reduce(function () {
            throw new \Exception('This should never run!');
        })
            , 'Reduce on a single container should be itself'
        );
    }

    public function test_map_callback($value = true)
    {
        $some = P\Some($value);
        $some->map(function () use ($value, $some) {
            $this->assertTrue(
                func_num_args() === 3
                , 'The signature of Map is ($value, $key, $container)'
            );
            $this->assertTrue(
                func_get_arg(0) === $value
                , 'The first parameter value should be equal to the value contained'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Key should be defined'
            );
            $this->assertTrue(
                func_get_arg(2) === $some
                , 'The map function should pass the container it uses'
            );
        });
    }

    public function test_map($value = true)
    {
        $original = P\Some($value);
        $duplicate = $original->map(function ($value) {
            return $value;
        });
        $this->assertInstanceOf(
            P\Some
            , $duplicate
            , 'The Map function should return the same type as the origonal'
        );
        $this->assertTrue(
            $original !== $duplicate
            , 'Map should return an instance of some different than itself'
        );
    }

    public function test_flatMap_callback($value = true)
    {
        $some = P\Some($value);
        $some->flatMap(function () use ($value, $some) {
            $this->assertTrue(
                func_num_args() === 3
                , 'The signature of flatMap is ($value, $key, $container)'
            );
            $this->assertTrue(
                func_get_arg(0) === $value
                , 'The first parameter value should be equal to the value contained'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Key should be defined'
            );
            $this->assertTrue(
                func_get_arg(2) === $some
                , 'The map function should pass the container it uses'
            );
            return P\Some($value);
        });
    }

    public function test_flatMap($value = true)
    {
        $some2 = P\Maybe($value);
        $some1 = P\Some($some2);

        $this->assertTrue(
            $some2 === ($some1->flatMap(function ($value) {
                return $value;
            }))
            , 'The Identity function should be able to flatten the value of $some1 to $some2'
        );
    }

    /**
     * Ensure the contract is help for flatmap that the container returned will be what was contained
     * @expectedException \Exception
     */
    public function test_flatMap_contract_broken($value = true)
    {
        P\Some($value)->flatMap(function ($value) {
            return $value;
        });
    }

    public function test_flatten($value = true)
    {
        $some2 = P\Maybe($value);
        $some1 = P\Some($some2);
        $this->assertTrue(
            ($some1->flatten()) === $some2
            , 'Flatten should take a nested Some and return the child Maybe'
        );
    }

    /**
     * @expectedException \Exception
     */
    public function test_flatten_contract_broken()
    {
        P\Some(true)->flatten();
    }

    public function test_filter_callback($value = true) {
        $some = P\Some($value);
        $some->filter(function () use ($value, $some) {
            $this->assertTrue(
                func_num_args() === 3
                , 'The signature of filter is ($value, $key, $container)'
            );
            $this->assertTrue(
                func_get_arg(0) === $value
                , 'The first parameter value should be equal to the value contained'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Key should be defined'
            );
            $this->assertTrue(
                func_get_arg(2) === $some
                , 'The map function should pass the container it uses'
            );
            return true;
        });
    }

    public function test_filter_senario_isArray($value = true, $expected = P\None) {
        $some = P\Some($value);
        $results = $some->filter(function ($val) { return is_array($val); } );
        $this->assertInstanceOf(
            $expected
            , $results
        );
        if ($expected === P\Some) {
            $this->assertTrue(
                $some === $results
                , 'When a filter is successful, Some should be an identity'
            );
        }
    }

    public function test_filterNot_callback($value = true) {
        $some = P\Some($value);
        $some->filterNot(function () use ($value, $some) {
            $this->assertTrue(
                func_num_args() === 3
                , 'The signature of filterNot is ($value, $key, $container)'
            );
            $this->assertTrue(
                func_get_arg(0) === $value
                , 'The first parameter value should be equal to the value contained'
            );
            $this->assertNotFalse(
                func_get_arg(1)
                , 'Key should be defined'
            );
            $this->assertTrue(
                func_get_arg(2) === $some
                , 'The map function should pass the container it uses'
            );
            return true;
        });
    }

}
