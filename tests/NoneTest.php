<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/8/2016
 * Time: 1:18 PM
 */

namespace tests\PHPixme;

use PHPixme as P;

class NoneTest extends \PHPUnit_Framework_TestCase
{
  public function test_None_constants()
  {
    $this->assertTrue(
      P\None::class === P\None
      , 'The constant for the Class and Function should be equal to the Class Path'
    );
    $this->assertTrue(
      function_exists(P\None)
      , 'The companion function exists for the class.'
    );
  }

  public function test_None_companion()
  {

    $this->assertStringEndsWith(
      '\None'
      , P\None
      , 'Ensure the constant ends with the function/class name'
    );
    $this->assertInstanceOf(
      P\None
      , P\None()
      , 'It should return an instance of none'
    );
  }

  public function test_None_aspect_Singleton()
  {
    $this->assertTrue(
      P\None() === P\None()
      , 'None should be a singleton'
    );
  }

  /**
   * @covers \PHPixme\None::getInstance
   */
  public function test_None_static_getInstance()
  {
    $none = P\None::getInstance();
    $this->assertInstanceOf(
      P\None,
      $none
      , 'Get Instance should return its instance of itself'
    );
    $this->assertTrue(
      $none === P\None::getInstance()
      , 'Get Instance should only return one instance of itself'
    );
  }

  public function test_None_aspects_traversable()
  {
    $this->assertInstanceOf(
      '\Traversable'
      , P\None()
      , 'None should be a traversable'
    );
  }

  public function test_None_aspects_CompleteCollection()
  {
    $this->assertInstanceOf(
      P\CollectionInterface::class
      , P\None()
      , 'It should have implemented natural transformations'
    );
  }


  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(P\None::class));
    $this->assertTrue(
      false !== array_search('PHPixme\ClosedTrait', $traits)
      , 'should be closed'
    );
  }
  
  public function test_static_of()
  {
    $this->assertTrue(
      P\None() === (P\None::of($this))
      , 'Of on None is its singleton'
    );
  }


  public function test_contains()
  {
    $this->assertFalse(
      P\None()->contains(1)
      , 'None should contain nothing'
    );
  }

  public function test_exists()
  {
    $this->assertFalse(
      P\None()->exists(
        function () {
          throw new \Exception('The callback should not run');
        }
      )
    );
  }

  public function test_forAll()
  {
    $this->assertTrue(
      P\None()->forAll(function () {
        throw new \Exception('The callback should not run');
      })
      , 'The result of forAll on None should be true.'
    );
  }

  public function test_forNone()
  {
    $this->assertTrue(
      P\None()->forNone(function () {
        throw new \Exception('This should never run!');
      })
      , 'The result of forNone on None should be true.'
    );
  }

  public function test_forSome()
  {
    $this->assertFalse(
      P\None()->forSome(function () {
        throw new \Exception('This should never run!');
      })
      , 'The result of forSome on None should be false.'
    );
  }

  /**
   * @expectedException \Exception
   */
  public function test_get()
  {
    P\None()->get();
  }

  public function test_getOrElse()
  {
    $this->assertTrue(
      P\None()->getOrElse(true)
      , 'None should return getOrElse default'
    );
  }

  public function test_isDefined()
  {
    $this->assertFalse(
      P\None()->isDefined()
      , 'None is not considered defined'
    );
  }

  public function test_orNull()
  {
    $this->assertNull(
      P\None()->orNull()
      , 'orNull should be Null on None'
    );
  }

  public function test_orElse()
  {
    $results = P\Maybe(10);
    $getResults = function () use ($results) {
      return $results;
    };
    $this->assertTrue(
      $results === (P\None()->orElse($getResults))
      , 'orElse on None should use the default HoF'
    );
  }

  /**
   * Ensure the contract on orElse is maintained
   * @expectedException \Exception
   */
  public function test_orElse_contract_broken()
  {
    P\None()->orElse(function () {
    });
  }

  public function test_toSeq()
  {
    $this->assertInstanceOf(
      P\Seq
      , P\None()->toSeq()
      , 'toSeq should produce a Sequence Type'
    );
  }

  /**
   * @expectedException \LengthException
   */
  public function test_reduce()
  {
    P\None()->reduce(function () {
    });
  }

  /**
   * @expectedException \LengthException
   */
  public function test_reduceRight()
  {
    P\None()->reduceRight(function () {
    });
  }

  public function test_fold()
  {
    $startVal = true;
    $this->assertTrue(
      $startVal === (P\None()->fold(function () {
        throw new \Exception('This should not run!');
      }, $startVal))
      , 'Folds on empty collections should return start values'
    );
  }

  public function test_foldRight()
  {
    $startVal = true;
    $this->assertTrue(
      $startVal === (P\None()->foldRight(function () {
        throw new \Exception('This should not run!');
      }, $startVal))
      , 'Folds on empty collections should return start values'
    );
  }

  public function test_map()
  {
    $none = P\None();
    $this->assertTrue(
      $none === ($none->map(function () {
        throw new \Exception('This should not run!');
      }))
      , 'Map on None is an identity'
    );
  }

  public function test_filter()
  {
    $none = P\None();
    $this->assertTrue(
      $none === ($none->filter(function () {
        throw new \Exception('This should not run!');
      }))
      , 'Filter on None is an identity'
    );
  }

  public function test_filterNot()
  {
    $none = P\None();
    $this->assertTrue(
      $none === ($none->filterNot(function () {
        throw new \Exception('This should not run!');
      }))
      , 'FilterNot on None is an identity'
    );
  }

  public function test_walk()
  {
    $times = 0;
    $result = P\None()->walk(function () use (&$times) {
      $times += 1;
    });
    $this->assertTrue(
      $result === P\None()
      , 'None->walk should return its own instance'
    );
    $this->assertTrue(
      0 === $times
      , 'Walk should run no times on None'
    );
  }

  public function test_toArray()
  {
    $arr = P\None()->toArray();
    $this->assertTrue(
      is_array($arr)
      , 'The value produced by None->toArray should be an array'
    );
    $this->assertTrue(
      0 === count($arr)
      , 'The length of the array produced by None->toArray should be 0'
    );
  }

  public function test_toLeft($value = true)
  {
    $ran = 0;
    $run = function () use ($value, &$ran) {
      $this->assertTrue(func_num_args() === 0, 'the callback should receive no arguments');
      $ran +=1;
      return $value;
    };
    $left = P\None()->toLeft($run);
    $this->assertEquals(1, $ran);
    $this->assertInstanceOf(P\Right::class, $left, 'toLeft on None should return a Right');
    $this->assertTrue($value === $left->merge());
  }

  public function test_toRight($value = true)
  {
    $ran = 0;
    $run = function () use ($value, &$ran) {
      $this->assertTrue(func_num_args() === 0, 'the callback should receive no arguments');
      $ran +=1;
      return $value;
    };
    $right = P\None()->toRight($run);
    $this->assertEquals(1, $ran);
    $this->assertInstanceOf(P\Left::class, $right, 'toRight on None should return a Left');
    $this->assertTrue($value === $right->merge());
  }

  public function test_isEmpty()
  {
    $this->assertTrue(
      P\None()->isEmpty()
      , 'None should be empty'
    );
  }

  public function test_find()
  {
    $this->assertInstanceOf(
      P\None
      , P\None()->find(function () {
      throw new \Exception('This should never run!');
    })
      , 'Find on none should be an identity'
    );
  }

  public function test_flatten()
  {
    $noneInstance = P\None();
    $this->assertTrue(
      $noneInstance === ($noneInstance->flatten())
      , 'The flatten method on None should return the same instance'
    );
  }

  public function test_flatMap()
  {
    $noneInstance = P\None();
    $this->assertTrue(
      $noneInstance === ($noneInstance->flatMap(function () {
        throw new \Exception('This should never run!');
      }))
      , 'The flatMap function should return the identity on None.'
    );
  }


  public function test_traversable_interface()
  {
    // time to test if the interface works
    $times = 0;
    foreach (P\None() as $key => $value) {
      $times += 1;
    }
    $this->assertTrue(
      0 === $times
      , 'None should always be at its end'
    );
  }

  public function test_count()
  {
    $this->assertEquals(
      0
      , P\None()->count()
      , 'None->count should always equal 0'
    );
  }
}
