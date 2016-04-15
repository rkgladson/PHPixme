<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 4:25 PM
 */

namespace tests\PHPixme;

use PHPixme as P;

class PotTest extends \PHPUnit_Framework_TestCase
{
  public function test_existential_companion()
  {
    $this->assertEquals(
      P\Pot::class
      , P\Pot
      , 'Ensure that class path is the constant value'
    );
    $this->assertTrue(
      function_exists(P\Pot::class)
      , 'Ensure that there is a function with the same name as the class'
    );
  }

  public function test_companion_function($value = true)
  {
    $this->assertInstanceOf(
      P\Pot::class
      , P\Pot($value)
      , 'The companion function aught to return an instance of its class'
    );
  }

  /**
   * @dataProvider aspectProvider
   * @param string $aspect
   */
  public function test_aspects($aspect)
  {
    $this->assertInstanceOf(
      $aspect
      , P\Pot(true)
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_of($value = true)
  {
    $this->assertInstanceOf(
      P\Pot::class
      , P\Pot::of($value)
    );
  }

  /**
   * @dataProvider filledCollectionProvider
   */
  public function test_from($collection = [1])
  {
    $this->assertInstanceOf(
      P\Pot::class
      , P\Pot::from($collection)
    );
  }

  /**
   * @expectedException \LengthException
   * @dataProvider emptyCollectionProvider
   */
  public function test_from_empty($collection = [])
  {
    P\Pot::from($collection);
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_invocation($value = true)
  {
    $this->assertTrue(
      $value === P\Pot($value)->get()
      , 'should return the contents from a companion($value)'
    );
    $this->assertTrue(
      $value === P\Pot::of($value)->__invoke()
      , 'should return the contents from ::of($value)'
    );
    $this->assertTrue(
      $value === P\Pot::from([$value])->__invoke()
      , 'should return the contents from ::from([$value])'
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_get($value = true)
  {
    $this->assertTrue(
      $value === P\Pot($value)->get()
      , 'should return the contents from a companion($value)'
    );
    $this->assertTrue(
      $value === P\Pot::of($value)->get()
      , 'should return the contents from ::of($value)'
    );
    $this->assertTrue(
      $value === P\Pot::from([$value])->get()
      , 'should return the contents from ::from([$value])'
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_constructor($value)
  {
    $instance = new P\Pot($value);
    $this->assertTrue(
      $value === $instance->get()
      , 'should return the contents from a companion($value)'
    );
    $this->assertTrue(
      $value === $instance()
      , 'should return the contents from a companion($value)'
    );

  }

  /**
   * @dataProvider valueProvider
   */
  public function test_map_callback($value)
  {
    $pot = P\Pot($value);
    $pot->map(function () use ($pot) {
      $this->assertTrue(
        3 === func_num_args()
        , '->map callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      $this->assertTrue(
        ($pot($key)) === $value
        , '->map callback $value should be equal to the value at $key'
      );
      $this->assertNotFalse(
        $key
        , 'Seq->map callback $key should be defined'
      );
      $this->assertTrue(
        $pot === $container
        , '->map callback $container should be itself'
      );
    });
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_map($value)
  {
    $pot = P\Pot($value);
    $pot2 = $pot->map(function ($x) {
      return $x;
    });
    $this->assertInstanceOf(
      P\Pot::class
      , $pot2
      , 'Map should only return its own kind'
    );
    $this->assertTrue(
      $value === $pot2()
      , 'map applied idenity should be exactly the same'
    );
  }

  public function test_fold_callback()
  {
  }

  public function test_fold()
  {
  }

  public function test_foldRight_callback()
  {
  }

  public function test_foldRight()
  {
  }

  public function test_flatMap_callback()
  {
  }

  public function test_flatMap_contract_broken()
  {
  }

  public function test_flatMap()
  {
  }

  public function test_flatten()
  {
  }

  public function test_flatten_contract_broken()
  {
  }

  public function test_forAll_callback()
  {
  }

  public function test_forAll()
  {
  }

  public function test_forNone_callback()
  {
  }

  public function test_forNone()
  {
  }

  public function test_forSome_callback()
  {
  }

  public function test_forSome()
  {
  }

  public function test_walk_callback()
  {
  }

  public function test_walk()
  {
  }

  public function test_toArray($value = true)
  {
    $this->assertEquals(
      [$value]
      , P\Pot($value)->toArray()
    );
  }

  public function test_find_callback()
  {
  }

  public function test_find()
  {
  }

  public function test_isEmpty($value = false)
  {
    $this->assertFalse(
      P\Pot($value)->isEmpty()
      , 'As a SingleCollection, it should never be empty'
    );
  }


  public function test_transversable($value)
  {

  }

  public function test_count($value = true)
  {
    $this->assertTrue(
      1 === P\Pot($value)->count()
      , 'Count should be the constant 1'
    );
  }

  public function aspectProvider()
  {
    return [
      [P\SingleCollectionInterface::class]
      , [P\CollectionInterface::class]
      , [\Countable::class]
      , [\Iterator::class]
    ];
  }

  public function valueProvider()
  {
    return [
      [[]]
      , [true]
      , [false]
      , [null]
      , [1]
      , ['^_^']
      , ['']
      , [P\Some(1)]
      , [new \ArrayIterator([1])]
    ];
  }

  public function filledCollectionProvider()
  {
    return [
      [[1]]
      , [[1, 2, 3]]
      , [P\Some(1)]
      , [new \ArrayIterator([1])]
    ];
  }

  public function emptyCollectionProvider()
  {
    return [
      [[]]
      , [P\None()]
      , [P\Seq::from([])]
      , [new \ArrayIterator([])]
    ];
  }
}