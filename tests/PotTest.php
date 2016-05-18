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

  public function test_not_a_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\Pot::class));
    $this->assertTrue(
      false === array_search('PHPixme\ClosedTrait', $traits)
      , 'should be not be closed'
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

  /**
   * @dataProvider foldCallbackProvider
   */
  public function test_fold_callback($value, $startValue)
  {
    $pot = P\Pot($value);
    $pot->fold(function ($lastVal) use ($startValue, $value, $pot) {
      $this->assertTrue(
        4 === func_num_args()
        , 'callback should receive four arguments'
      );
      $this->assertTrue(
        func_get_arg(0) === $startValue
        , 'callback $prevVal should be the $startValue'
      );
      $this->assertTrue(
        func_get_arg(1) === $value
        , '$value should be its contents'
      );
      $this->assertNotFalse(
        func_get_arg(2)
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        func_get_arg(3) === $pot
        , 'callback $container should be itself'
      );

      return $lastVal;
    }, $startValue);
  }

  /**
   * @dataProvider foldSenarioProvider
   */
  public function test_fold($fn, $value, $startVal, $expectation)
  {
    $this->assertEquals(
      $expectation
      , P\Pot($value)->fold($fn, $startVal)
    );
  }

  /**
   * @dataProvider foldCallbackProvider
   */
  public function test_foldRight_callback($value, $startValue)
  {
    $pot = P\Pot($value);
    $pot->foldRight(function ($lastVal) use ($startValue, $value, $pot) {
      $this->assertTrue(
        4 === func_num_args()
        , 'callback should receive four arguments'
      );
      $this->assertTrue(
        func_get_arg(0) === $startValue
        , 'callback $prevVal should be the $startValue'
      );
      $this->assertTrue(
        func_get_arg(1) === $value
        , '$value should be its contents'
      );
      $this->assertNotFalse(
        func_get_arg(2)
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        func_get_arg(3) === $pot
        , 'callback $container should be itself'
      );

      return $lastVal;
    }, $startValue);
  }

  /**
   * @dataProvider foldSenarioProvider
   *
   */
  public function test_foldRight(callable $fn, $value, $startVal, $expectation)
  {
    $this->assertEquals(
      $expectation
      , P\Pot($value)->foldRight($fn, $startVal)
    );
  }

  public function test_flatMap_callback($value = true)
  {
    $pot = P\Pot($value);
    $pot->flatMap(function () use ($value, $pot) {
      $this->assertEquals(
        3
        , func_num_args()
        , 'callback should recieve 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'callback $value should equal to the stored value'
      );
      $this->assertTrue(
        false !== func_get_arg(1)
        , 'callback $key should be defined'
      );
      $this->assertTrue(
        func_get_arg(2) === $pot
        , 'callback $collection should be the object being acted upon'
      );
      return $pot;
    });
  }

  /**
   * @expectedException \UnexpectedValueException
   */
  public function test_flatMap_contract_broken($value = true)
  {
    P\Pot($value)->flatMap(function () use ($value) {
      return $value;
    });
  }

  public function test_flatMap()
  {
    $idenity = function ($doneCare1, $donCare2, $myself) {
      return $myself;
    };
    $clone = function ($value) {
      return P\Pot($value);
    };

    $pot = P\Pot(P\None());
    $this->assertTrue(
      $pot === $pot->flatMap($idenity)
      , 'FlatMap is allowed to return its own instance'
    );
    $potClone = $pot->flatMap($clone);
    $this->assertInstanceOf(
      P\Pot::class
      , $potClone
      , 'Flatmap should always return its own kind.'
    );
    $this->assertTrue(
      $pot !== $potClone
      , 'When applyed to an operation, the flatmap need not return the exact instance of itself.'
    );
  }

  public function test_flatten($value = true)
  {
    $level1 = P\Pot($value); $level2 = P\Pot($level1);
    $unwrapped = $level2->flatten();
    $this->assertInstanceOf(
      P\Pot::class
      , $unwrapped
      , 'the return value should be of type Pot'
    );
    $this->assertTrue(
      $unwrapped === $level1
      , 'The unwrapped results should be that of the contents of the nested value.'
    );
    $this->assertEquals(
      $value
      ,$unwrapped->get()
      , 'The value contained by the flattened value should be equal to the origonal double nested value'
    );

  }

  /**
   * @expectedException \UnexpectedValueException
   */
  public function test_flatten_contract_broken($value = true)
  {
    P\Pot($value)->flatten();
  }

  public function test_forAll_callback($value = true)
  {
    $run = 0;
    $pot = P\Pot($value);
    $pot->forAll(function () use ($value, $pot, &$run) {
      $this->assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      $this->assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    $this->assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_forAll($value = true, $notValue = null)
  {
    $pot = P\Pot($value);
    $this->assertTrue(
      $pot->forAll(function ($v) use ($value) {return $value === $v;})
      , 'A true resultant for when the function returns true'
    );
    $this->assertFalse(
      $pot->forAll(function ($v) use ($notValue) {return $notValue === $v;})
      , 'a false resultant when the function returns false'
    );
  }

  public function test_forNone_callback($value = true)
  {
    $run = 0;
    $pot = P\Pot($value);
    $pot->forNone(function () use ($value, $pot, &$run) {
      $this->assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      $this->assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    $this->assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_forNone($value = true, $notValue = null)
  {
    $pot = P\Pot($value);
    $this->assertFalse(
      $pot->forNone(function ($v) use ($value) {return $value === $v;})
      , 'A false resultant for when the function returns true'
    );
    $this->assertTrue(
      $pot->forNone(function ($v) use ($notValue) {return $notValue === $v;})
      , 'a True resultant when the function returns false'
    );
  }

  public function test_forSome_callback($value = true)
  {
    $run = 0;
    $pot = P\Pot($value);
    $pot->forSome(function () use ($value, $pot, &$run) {
      $this->assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      $this->assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    $this->assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_forSome($value = true, $notValue = null)
  {
    $pot = P\Pot($value);
    $this->assertTrue(
      $pot->forSome(function ($v) use ($value) {return $value === $v;})
      , 'A true resultant for when the function returns true'
    );
    $this->assertFalse(
      $pot->forSome(function ($v) use ($notValue) {return $notValue === $v;})
      , 'a false resultant when the function returns false'
    );
  }

  public function test_walk_callback($value = true)
  {
    $run = 0;
    $pot = P\Pot($value);
    $pot->walk(function () use ($value, $pot, &$run) {
      $this->assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      $this->assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
    });
    $this->assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_walk($value = '^_^')
  {
    $this->expectOutputString($value);
    P\Pot($value)->walk(P\unary('printf'));
  }

  public function test_toArray($value = true)
  {
    $this->assertEquals(
      [$value]
      , P\Pot($value)->toArray()
    );
  }

  public function test_find_callback($value = true)
  {
    $pot = P\Pot($value); 
    $run = 0;
    $pot->find(function () use ($value, $pot, &$run) {
      $this->assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      $this->assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      $this->assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      $this->assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    $this->assertEquals(
      1
      , $run
      , 'the callback should run once'
    );
  }

  /**
   * @param $value
   * @param callable $fn
   * @param $isNone
   * @dataProvider findProvider
   */
  public function test_find($value, callable $fn, $isNone)
  {
    $resultant = P\Pot($value)->find($fn);
    $this->assertInstanceOf(
      P\Maybe::class
      , $resultant
      , 'The type should be some or none'
    );
    $this->assertTrue(
      $isNone === $resultant->isEmpty()
      , 'the resultant should be the expected Maybe type'
    );
    $this->{$isNone ? 'assertFalse' : 'assertTrue'}(
      $resultant->contains($value)
      , 'the contents should or should not contain the value based on the outcome of the function'
    );
  }

  public function test_isEmpty($value = false)
  {
    $this->assertFalse(
      P\Pot($value)->isEmpty()
      , 'As a SingleCollection, it should never be empty'
    );
  }


  public function test_transversable($value = true)
  {
    $iterations = 0;
    $k_ = null;
    $v_ = null;
    foreach (P\Pot($value) as $k => $v) {
      $k_ = $k;
      $v_ = $v;
      $iterations += 1;
    }
    $this->assertEquals(
      1
      , $iterations
      , 'It should terminate after one traversal.'
    );
    $this->assertEquals(
      $value
      , $v_
      , 'the last stored value should be equal to the value of the container'
    );
    $this->assertEquals(
      0
      , $k_
      , 'the last stored key should be the \'index\' of the container'
    );
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
      [P\CollectionInterface::class]
      , [P\UnaryApplicativeInterface::class]
      , [\Countable::class]
      , [\IteratorAggregate::class]
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

  public function foldCallbackProvider()
  {
    return [
      [1, 0]
      , ['9', '']
    ];
  }

  public function foldSenarioProvider()
  {
    $add = function ($x, $y) {
      return $x + $y;
    };
    $multiply = function ($x, $y) {
      return $x * $y;
    };
    $concat = function ($x, $y) {
      return $x . $y;
    };
    return [
      [$add, 2, 2, 4]
      , [$multiply, 4, 2, 8]
      , [$concat, 'bar', 'foo', 'foobar']
    ];
  }
  public function findProvider() {
    return [
      'found'=>[1, function ($value) { return $value === 1; }, false]
      , 'not found' => ['', function ($value) {return $value === 1;}, true]
    ];
  }

}