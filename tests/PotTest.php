<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 4/15/2016
 * Time: 4:25 PM
 */

namespace tests\PHPixme;

use PHPixme as P;
use PHPixme\exception\MutationException;
use PHPixme\Pot as testSubject;
use const PHPixme\Pot as testConst;
use function PHPixme\Pot as testFn;

class PotTest extends \PHPUnit_Framework_TestCase
{
  public function test_existential_companion()
  {
    self::assertEquals(
      testSubject::class
      , testConst
      , 'Ensure that class path is the constant value'
    );
    self::assertTrue(
      function_exists(testSubject::class)
      , 'Ensure that there is a function with the same name as the class'
    );
  }

  public function test_companion_function($value = true)
  {
    $this->assertInstanceOf(
      P\Pot::class
      , testFn($value)
      , 'The companion function aught to return an instance of its class'
    );
  }

  /**
   * @dataProvider aspectProvider
   * @param string $aspect
   */
  public function test_aspects($aspect)
  {
    self::assertInstanceOf(
      $aspect
      , testFn(true)
    );
  }

  public function test_patience()
  {
    $this->expectException(MutationException::class);
    (new testSubject(0))->__construct(1);
  }

  public function test_not_a_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    self::assertTrue(
      false === array_search('PHPixme\ClosedTrait', $traits)
      , 'should be not be closed'
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_of($value = true)
  {
    self::assertInstanceOf(
      testSubject::class
      , testSubject::of($value)
    );
  }

  public function test_fromThrowable($value = true, $message = 'Hoi!', $code = 404) {
    $exception = new \Exception($message, $code);
    $pottedException = testSubject::fromThrowable($exception, $value);
    self::assertInstanceOf(testSubject::class, $pottedException);
    self::assertEquals($value, $pottedException->get());
    self::assertEquals($message, $pottedException->getMessage());
    self::assertEquals($code, $pottedException->getCode());
    self::assertTrue($exception === $pottedException->getPrevious());
  }


  /**
   * @dataProvider valueProvider
   */
  public function test_invocation($value = true)
  {
    self::assertTrue(
      $value === testFn($value)->get()
      , 'should return the contents from a companion($value)'
    );
    self::assertTrue(
      $value === testSubject::of($value)->__invoke()
      , 'should return the contents from ::of($value)'
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_get($value = true)
  {
    self::assertTrue(
      $value === testFn($value)->get()
      , 'should return the contents from a companion($value)'
    );
    self::assertTrue(
      $value === testSubject::of($value)->get()
      , 'should return the contents from ::of($value)'
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function test_constructor($value)
  {
    $instance = new P\Pot($value);
    self::assertTrue(
      $value === $instance->get()
      , 'should return the contents from a companion($value)'
    );
    self::assertTrue(
      $value === $instance()
      , 'should return the contents from a companion($value)'
    );

  }

  /**
   * @dataProvider valueProvider
   */
  public function test_map_callback($value)
  {
    $pot = testFn($value);
    $pot->map(function () use ($pot) {
      self::assertTrue(
        3 === func_num_args()
        , '->map callback should receive three arguments'
      );
      $value = func_get_arg(0);
      $key = func_get_arg(1);
      $container = func_get_arg(2);

      self::assertTrue(
        ($pot($key)) === $value
        , '->map callback $value should be equal to the value at $key'
      );
      self::assertNotFalse(
        $key
        , 'Seq->map callback $key should be defined'
      );
      self::assertTrue(
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
    $pot = testFn($value);
    $pot2 = $pot->map(function ($x) {
      return $x;
    });
    self::assertInstanceOf(
      testSubject::class
      , $pot2
      , 'Map should only return its own kind'
    );
    self::assertTrue(
      $value === $pot2()
      , 'map applied idenity should be exactly the same'
    );
  }

  /**
   * @dataProvider foldCallbackProvider
   */
  public function test_fold_callback($value, $startValue)
  {
    $pot = testFn($value);
    $pot->fold(function ($lastVal) use ($startValue, $value, $pot) {
      self::assertTrue(
        4 === func_num_args()
        , 'callback should receive four arguments'
      );
      self::assertTrue(
        func_get_arg(0) === $startValue
        , 'callback $prevVal should be the $startValue'
      );
      self::assertTrue(
        func_get_arg(1) === $value
        , '$value should be its contents'
      );
      self::assertNotFalse(
        func_get_arg(2)
        , 'callback $key should be defined'
      );
      self::assertTrue(
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
    self::assertEquals(
      $expectation
      , testFn($value)->fold($fn, $startVal)
    );
  }

  /**
   * @dataProvider foldCallbackProvider
   */
  public function test_foldRight_callback($value, $startValue)
  {
    $pot = testFn($value);
    $pot->foldRight(function ($lastVal) use ($startValue, $value, $pot) {
      self::assertTrue(
        4 === func_num_args()
        , 'callback should receive four arguments'
      );
      self::assertTrue(
        func_get_arg(0) === $startValue
        , 'callback $prevVal should be the $startValue'
      );
      self::assertTrue(
        func_get_arg(1) === $value
        , '$value should be its contents'
      );
      self::assertNotFalse(
        func_get_arg(2)
        , 'callback $key should be defined'
      );
      self::assertTrue(
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
    self::assertEquals(
      $expectation
      , testFn($value)->foldRight($fn, $startVal)
    );
  }

  public function test_flatMap_callback($value = true)
  {
    $pot = testFn($value);
    $pot->flatMap(function () use ($value, $pot) {
      self::assertEquals(
        3
        , func_num_args()
        , 'callback should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'callback $value should equal to the stored value'
      );
      self::assertTrue(
        false !== func_get_arg(1)
        , 'callback $key should be defined'
      );
      self::assertTrue(
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
    testFn($value)->flatMap(function () use ($value) {
      return $value;
    });
  }

  public function test_flatMap()
  {
    $idenity = function ($doneCare1, $donCare2, $myself) {
      return $myself;
    };
    $clone = function ($value) {
      return testFn($value);
    };

    $pot = testFn(P\None());
    self::assertTrue(
      $pot === $pot->flatMap($idenity)
      , 'FlatMap is allowed to return its own instance'
    );
    $potClone = $pot->flatMap($clone);
    self::assertInstanceOf(
      testSubject::class
      , $potClone
      , 'Flatmap should always return its own kind.'
    );
    self::assertTrue(
      $pot !== $potClone
      , 'When applyed to an operation, the flatmap need not return the exact instance of itself.'
    );
  }

  public function test_flatten($value = true)
  {
    $level1 = testFn($value); $level2 = testFn($level1);
    $unwrapped = $level2->flatten();
    self::assertInstanceOf(
      testSubject::class
      , $unwrapped
      , 'the return value should be of type Pot'
    );
    self::assertTrue(
      $unwrapped === $level1
      , 'The unwrapped results should be that of the contents of the nested value.'
    );
    self::assertEquals(
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
    testFn($value)->flatten();
  }

  public function test_forAll_callback($value = true)
  {
    $run = 0;
    $pot = testFn($value);
    $pot->forAll(function () use ($value, $pot, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    self::assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_forAll($value = true, $notValue = null)
  {
    $pot = testFn($value);
    self::assertTrue(
      $pot->forAll(function ($v) use ($value) {return $value === $v;})
      , 'A true resultant for when the function returns true'
    );
    self::assertFalse(
      $pot->forAll(function ($v) use ($notValue) {return $notValue === $v;})
      , 'a false resultant when the function returns false'
    );
  }

  public function test_forNone_callback($value = true)
  {
    $run = 0;
    $pot = testFn($value);
    $pot->forNone(function () use ($value, $pot, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    self::assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_forNone($value = true, $notValue = null)
  {
    $pot = testFn($value);
    self::assertFalse(
      $pot->forNone(function ($v) use ($value) {return $value === $v;})
      , 'A false resultant for when the function returns true'
    );
    self::assertTrue(
      $pot->forNone(function ($v) use ($notValue) {return $notValue === $v;})
      , 'a True resultant when the function returns false'
    );
  }

  public function test_forSome_callback($value = true)
  {
    $run = 0;
    $pot = testFn($value);
    $pot->forSome(function () use ($value, $pot, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    self::assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_forSome($value = true, $notValue = null)
  {
    $pot = testFn($value);
    self::assertTrue(
      $pot->forSome(function ($v) use ($value) {return $value === $v;})
      , 'A true resultant for when the function returns true'
    );
    self::assertFalse(
      $pot->forSome(function ($v) use ($notValue) {return $notValue === $v;})
      , 'a false resultant when the function returns false'
    );
  }

  public function test_walk_callback($value = true)
  {
    $run = 0;
    $pot = testFn($value);
    $pot->walk(function () use ($value, $pot, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
    });
    self::assertEquals(1, $run, 'the callback should of ran once');
  }

  public function test_walk($value = '^_^')
  {
    $this->expectOutputString($value);
    testFn($value)->walk(P\unary('printf'));
  }

  public function test_toArray($value = true)
  {
    self::assertEquals(
      [$value]
      , testFn($value)->toArray()
    );
  }

  public function test_find_callback($value = true)
  {
    $pot = testFn($value); 
    $run = 0;
    $pot->find(function () use ($value, $pot, &$run) {
      self::assertEquals(
        3
        , func_num_args()
        , 'find should recieve 3 arguments'
      );
      self::assertTrue(
        $value === func_get_arg(0)
        , 'the value should be exactly the value that was stored in the pot'
      );
      self::assertNotFalse(
        func_get_arg(1)
        , 'the key argument should be defined'
      );
      self::assertTrue(
        $pot === func_get_arg(2)
        , 'the container should be the third argument'
      );
      $run +=1;
      return true;
    });
    self::assertEquals(
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
    $resultant = testFn($value)->find($fn);
    self::assertInstanceOf(
      P\Maybe::class
      , $resultant
      , 'The type should be some or none'
    );
    self::assertTrue(
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
    self::assertFalse(
      testFn($value)->isEmpty()
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
    self::assertEquals(
      1
      , $iterations
      , 'It should terminate after one traversal.'
    );
    self::assertEquals(
      $value
      , $v_
      , 'the last stored value should be equal to the value of the container'
    );
    self::assertEquals(
      0
      , $k_
      , 'the last stored key should be the \'index\' of the container'
    );
  }

  public function test_count($value = true)
  {
    self::assertTrue(
      1 === testFn($value)->count()
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