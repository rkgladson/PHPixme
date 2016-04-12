<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/13/2016
 * Time: 10:12 AM
 */

namespace tests\PHPixme;

use PHPixme as P;
const Closure = \Closure::class;

class _Test extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertInstanceOf(
      \stdClass::class
      , P\_()
      , 'the placeholder should be an instance of standard class'
    );
    $this->assertTrue(
      P\_() === P\_()
      , 'the placeholder value should not change between executions.'
    );
  }
}

class curryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\curry'
      , P\curry
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\curry)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };
    $param3 = P\curry(3, $countArgs);
    $this->assertInstanceOf(
      Closure
      , $param3
      , 'Curry should return a closure'
    );
    $this->assertInstanceOf(
      Closure
      , $param3(1)
      , 'Curried functions should be still a closure when partially applied'
    );
    $this->assertEquals(
      3
      , $param3(1, 2, 3)
      , 'Curried functions should run when the minimum arguments are applied'
    );
    $this->assertEquals(
      4
      , $param3(1, 2, 3, 4)
      , 'Curried functions may pass more than the minimum arity is passed'
    );
    $this->assertEquals(
      3
      , $param3(1)->__invoke(2)->__invoke(3)
      , 'Curried functions should be able to be chained'
    );
    $curry2 = P\curry(2);
    $this->assertInstanceOf(
      Closure
      , $curry2
      , 'The curry function itself should be curried'
    );
    $this->assertInstanceOf(
      Closure
      , $curry2($countArgs)
      , 'The partially applied curry function should produce a closure'
    );
    $this->assertEquals(
      2
      , $curry2($countArgs)->__invoke(1, 2)
      , 'The partially applied version of curry should behave just like the non-partially applied one'
    );
  }

  public function test_with_placeholder()
  {
    $equivlentArray5 = [1, 2, 3, 4, 5];
    $equivlentArray7 = [1, 2, 3, 4, 5, 6, 7];
    $getArgs = function () {
      return func_get_args();
    };
    $this->assertEquals(
      P\curry(5, $getArgs)
        ->__invoke(1, P\_(), 3, P\_(), 5)
        ->__invoke(2)
        ->__invoke(4)
      , $equivlentArray5
      , 'When within, the placeholders in Curry should be filled in one by one.'
    );
    $this->assertEquals(
      P\curry(5, $getArgs)
        ->__invoke(P\_(), P\_(), 3, 4, 5)
        ->__invoke(1, 2)
      , $equivlentArray5
      , 'When using placeholders with curry, it should still be able to have a varadic follow up.'
    );
    $this->assertEquals(
      P\curry(5, $getArgs)
        ->__invoke(P\_(), P\_(), P\_(), P\_(), 5, 6)
        ->__invoke(1, P\_(), P\_(), P\_(), 7)
        ->__invoke(2, 3, 4)
      , $equivlentArray7
      , 'The function should be able to exceed its arity'
    );
  }
}

class nAryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\nAry'
      , P\nAry
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\nAry)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };

    $this->assertInstanceOf(
      Closure
      , P\nAry(1)
      , 'nAry should be partially applied'
    );
    $this->assertEquals(
      1
      , P\nAry(1)->__invoke($countArgs)->__invoke(1, 2, 3)
      , 'nAry Partially applied should still produce a wrapped function that eats arguments'
    );
    $this->assertInstanceOf(
      Closure
      , P\nAry(1, $countArgs)
      , 'nAry fully applied should produce a closure'
    );
    $this->assertEquals(
      1
      , P\nAry(1, $countArgs)->__invoke(1, 2, 3, 4)
      , 'fully applied should still work the same as partially applied, eating arguments'
    );
  }
}

class unaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\unary'
      , P\unary
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\unary)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };

    $this->assertInstanceOf(
      Closure
      , P\unary($countArgs)
      , 'unary should return a closure'
    );

    $this->assertEquals(
      1
      , P\unary($countArgs)->__invoke(1, 2, 3)
      , 'Unary should eat all but one argument'
    );
  }
}

class binaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\binary'
      , P\binary
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\binary)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };

    $this->assertInstanceOf(
      Closure
      , P\binary($countArgs)
      , 'binary should return a closure'
    );

    $this->assertEquals(
      2
      , P\binary($countArgs)->__invoke(1, 2, 3)
      , 'binary should eat all but two arguments'
    );
  }
}

class ternaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\ternary'
      , P\ternary
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\ternary)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };
    $this->assertInstanceOf(
      Closure
      , P\ternary($countArgs)
      , 'ternary should return a closure'
    );

    $this->assertEquals(
      3
      , P\ternary($countArgs)->__invoke(1, 2, 3, 4)
      , 'ternary should eat all but three arguments'
    );
  }
}

class nullaryTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\nullary'
      , P\nullary
      , 'Ensure the constant is assigned to its function name'
    );

    $this->assertTrue(
      function_exists(P\nullary)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $countArgs = function () {
      return func_num_args();
    };

    $this->assertInstanceOf(
      Closure
      , P\nullary($countArgs)
      , 'nullary should return a closure'
    );

    $this->assertEquals(
      0
      , P\nullary($countArgs)->__invoke(1, 2, 3, 4)
      , 'nullary should eat all arguments'
    );
  }
}

class flipTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\flip'
      , P\flip
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\flip)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $getArgs = function () {
      return func_get_args();
    };
    $this->assertInstanceOf(
      Closure
      , P\flip($getArgs)
      , 'Flip should return a closure'
    );

    $this->assertEquals(
      [2, 1, 3, 4, 5]
      , P\flip($getArgs)->__invoke(1, 2, 3, 4, 5)
      , 'Flip should flip the first two arguments'
    );
    $this->assertInstanceOf(
      Closure
      , P\flip($getArgs)->__invoke(1)
      , 'Flip partially applied should return a closure'
    );

    $this->assertEquals(
      [2, 1, 3, 4, 5]
      , P\flip($getArgs)->__invoke(1)->__invoke(2, 3, 4, 5)
      , 'Flip partially applied should return the flipped arguments'
    );
  }
}

class combineTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\combine'
      , P\combine
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\combine)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $this->assertInstanceOf(
      Closure
      , P\combine('json_encode', 'array_reverse')
      , 'combine should return a closure'
    );

    $this->assertInstanceOf(
      Closure
      , P\combine('json_encode')
      , 'combine should be a curried function'
    );

    $array = [1, 2, 3];
    $this->assertEquals(
      json_encode(array_reverse($array))
      , P\combine('json_encode')->__invoke('array_reverse')->__invoke($array)
      , 'combine should be able to chain the outputs to produce hof results'
    );
    $this->assertEquals(
      json_decode(json_encode(array_reverse($array)))
      , P\combine('json_decode', 'json_encode', 'array_reverse')->__invoke($array)
      , 'combine should be able to chain more than two callables and still produce results'
    );
  }
}

class pipeTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pipe'
      , P\pipe
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\pipe)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return()
  {
    $this->assertInstanceOf(
      Closure
      , P\pipe('array_reverse', 'json_encode')
      , 'pipe should return a closure'
    );

    $this->assertInstanceOf(
      Closure
      , P\combine('array_reverse')
      , 'pipe should be a curried function'
    );

    $array = [1, 2, 3];
    $this->assertEquals(
      json_encode(array_reverse($array))
      , P\pipe('array_reverse')->__invoke('json_encode')->__invoke($array)
      , 'pipe should be able to chain the outputs to produce hof results'
    );
    $this->assertEquals(
      json_decode(json_encode(array_reverse($array)))
      , P\pipe('array_reverse', 'json_encode', 'json_decode')->__invoke($array)
      , 'pipe should be able to handle more than two functions in the chain'
    );
  }
}

class K_estrelTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\K'
      , P\K
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\K)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true, $notValue = false)
  {
    $this->assertInstanceOf(
      Closure
      , P\K($value)
      , 'K should return a closure'
    );
    $this->assertEquals(
      $value
      , P\K($value)->__invoke($notValue)
      , 'K resultant closure should return the constant that has been closed'
    );
  }
}

class KI_teTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\KI'
      , P\KI
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\KI)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true, $notValue = false)
  {
    $this->assertInstanceOf(
      Closure
      , P\KI($value)
      , 'KI should return a closure'
    );
    $this->assertEquals(
      $notValue
      , P\KI($value)->__invoke($notValue)
      , 'K resultant closure should ignore the constant and return the argument it recieves'
    );
  }
}

class I_diotBirdTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\I'
      , P\I
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\I)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $this->assertEquals(
      $value
      , P\I($value)
      , 'The notoriously simple idiot bird proves useful in unusual places'
    );
  }
}

class S_tarlingTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\S'
      , P\S
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\S)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = true)
  {
    $this->assertInstanceOf(
      Closure
      , P\S(P\I)
      , 'Starling should be able to be partially applied'
    );
    $this->assertEquals(
      $value
      , P\S(P\K)->__invoke(P\K($value))->__invoke($value)
      , 'S(K, K($value))($value) === $value is one of the more basic proofs to Starling'
    );

  }

  public function test_scenario_tupleMaker($array = [1, 2, 3, 4])
  {
    // Test to see if we can fix array_map through starling to get the key with the value
    $kvTupple = function ($v, $k) {
      return [$k, $v];
    };
    $kvMap = P\ternary('array_map')->__invoke($kvTupple);
    $this->assertEquals(
      array_map($kvTupple, $array, array_keys($array))
      , P\S($kvMap, 'array_keys')->__invoke($array)
    );
  }
}

class tapTest extends \PHPUnit_Framework_TestCase
{
  public function test_tap_constant()
  {
    $this->assertStringEndsWith(
      '\tap'
      , P\tap
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\tap)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_tap($value = 1)
  {
    $consoleLog = P\tap('printf');
    $this->assertInstanceOf(
      Closure
      , $consoleLog
      , 'Tap should return a closure'
    );
    $this->expectOutputString((string)$value);
    $this->assertTrue(
      $consoleLog($value) === $value
      , 'Tap should not modify the value that passes through it.'
    );
  }
}

class beforeTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\before'
      , P\before
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\before)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = 5)
  {
    $printArgs = P\before(function () {printf(json_encode(func_get_args()));});
    $expectingSideEffect = json_encode([$value]);
    $this->assertInstanceOf(
      Closure
      , $printArgs
      , 'The frist argument shal produce a Closure'
    );
    $echoArgsIdiot = $printArgs(function($x) {return $x;});
    $this->assertInstanceOf(
      Closure
      , $echoArgsIdiot
      , 'The secon argument shal produce a closure'
    );

    $this->expectOutputString($expectingSideEffect.$expectingSideEffect);
    $this->assertTrue(
      $value === $echoArgsIdiot($value)
      , 'Before shalt not alter the output'
    );
    $this->assertTrue(
      ($value + 1) === $printArgs(function($x) { return $x + 1;})->__invoke($value)
      , 'Before shalt not alter the input'
    );
  }
}

class afterTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\after'
      , P\after
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\after)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = [5,5])
  {
    $printResult = P\after('printf');
    $multiply = function($x, $y) {return $x * $y;};
    $verboseMultiplication = $printResult($multiply);
    $output = call_user_func_array($multiply, $value);

    $this->assertInstanceOf(
      Closure
      , $printResult
      , 'The frist argument shal produce a Closure'
    );
    $this->assertInstanceOf(
      Closure
      , $verboseMultiplication
      , 'The secon argument shal produce a closure'
    );

    $this->expectOutputString((string) $output);
    $this->assertTrue(
      $output === call_user_func_array($verboseMultiplication, $value)
      , 'after shalt not alter the output'
    );
  }
}

class foldTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\fold'
      , P\fold
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\fold)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($value, $expVal, $expKey)
  {
    $startVal = 1;
    P\fold(function () use ($startVal, $value, $expVal, $expKey) {
      $this->assertEquals(
        4
        , func_num_args()
        , 'fold callback should receive four arguments'
      );
      $this->assertEquals(
        $startVal
        , func_get_arg(0)
        , 'fold callback $prevVal should equal startValue'
      );
      $this->assertEquals(
        $expVal
        , func_get_arg(1)
        , 'fold callback $value should equal to expected value'
      );
      $this->assertEquals(
        $expKey
        , func_get_arg(2)
        , 'fold callback $key should equal to expected key'
      );
      if (is_object($value)) {
        $this->assertTrue(
          $value === func_get_arg(3)
          , 'fold callback $container should be the same instance as the object'
        );
      } else {
        $this->assertEquals(
          $value
          , func_get_arg(3)
          , 'fold callback $container should equal to the array'
        );
      }

      return func_get_arg(0);
    }, $startVal, $value);
  }

  public function test_return($value = 1, $array = [1, 2, 3, 4])
  {
    $this->assertInstanceOf(
      Closure
      , P\fold(P\I, $value)
      , 'fold when partially applied should return a closure'
    );
    $this->assertEquals(
      $value
      , P\fold(P\I, $value)->__invoke($array)
      , 'An idiot applied to fold should always return the start value'
    );
    $this->assertEquals(
      $array[count($array) - 1]
      , P\fold(P\flip(P\I), $value, $array)
      , 'The flipped idiot applied to reduce should always return the last unless empty'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $startVal, $action, $expected)
  {
    $this->assertEquals(
      $expected
      , P\fold($action, $startVal, $arrayLike)
    );
  }

  public function callbackProvider()
  {
    return [
      'array callback' => [
        [1], 1, 0
      ]
      , 'traversable callback' => [
        new \ArrayIterator([1]), 1, 0
      ]
      , 'natural interface callback' => [
        P\Seq([1]), 1, 0
      ]
    ];
  }

  public function scenarioProvider()
  {
    $add = function ($a, $b) {
      return $a + $b;
    };
    return [
      'add simple empty array' => [
        []
        , 0
        , $add
        , 0
      ]
      , 'add simple S[]' => [
        P\Seq::of()
        , 0
        , $add
        , 0
      ]
      , 'add simple None' => [
        P\None()
        , 0
        , $add
        , 0
      ]
      , 'ArrayObject[]' => [
        new \ArrayIterator([])
        , 0
        , $add
        , 0
      ]
      , 'add 1+2+3' => [
        [1, 2, 3]
        , 0
        , $add
        , 6
      ]
      , 'add S[1,2,3]' => [
        P\Seq::of(1, 2, 3)
        , 0
        , $add
        , 6
      ]
      , 'Some(2)+2' => [
        P\Some(2)
        , 2
        , $add
        , 4
      ]
      , 'add ArrayObject[1,2,3]' => [
        new \ArrayIterator([1, 2, 3])
        , 0
        , $add
        , 6
      ]
    ];
  }
}

class reduceTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\reduce'
      , P\reduce
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\reduce)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider undefinedBehaviorProvider
   * @expectedException \Exception
   */
  public function test_contract_violation($arrayLike = [])
  {
    P\reduce(P\I, $arrayLike);
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $firstVal, $expVal, $expKey)
  {
    P\reduce(function () use ($firstVal, $expVal, $expKey, $arrayLike) {
      $this->assertEquals(
        4
        , func_num_args()
        , 'reduce callback should receive four arguments'
      );
      $this->assertEquals(
        $firstVal
        , func_get_arg(0)
        , 'reduce callback $prevVal should equal startValue'
      );
      $this->assertEquals(
        $expVal
        , func_get_arg(1)
        , 'reduce callback should equal to expected value'
      );
      $this->assertEquals(
        $expKey
        , func_get_arg(2)
        , 'reduce callback $key should equal to expected key'
      );
      if (is_object($arrayLike)) {
        $this->assertTrue(
          $arrayLike === func_get_arg(3)
          , 'reduce callback $container should be the same instance as the source data'
        );
      } else {
        $this->assertEquals(
          $arrayLike
          , func_get_arg(3)
          , '$container should equal to the array being reduced'
        );
      }

      return func_get_arg(0);
    }, $arrayLike);
  }

  public function test_return($array = [1, 2, 3, 4])
  {
    $this->assertInstanceOf(
      Closure
      , P\reduce(P\I)
      , 'reduce when partially applied should return a closure'
    );
    $this->assertEquals(
      $array[0]
      , P\reduce(P\I)->__invoke($array)
      , 'An idiot applied to fold should always return the start value'
    );
    $this->assertEquals(
      $array[count($array) - 1]
      , P\reduce(P\flip(P\I), $array)
      , 'The flipped idiot applied to reduce should always return the last'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $action, $expected)
  {
    $this->assertEquals(
      $expected
      , P\reduce($action, $arrayLike)
    );
  }

  public function undefinedBehaviorProvider()
  {
    return [
      '[]' => [[]]
      , 'None' => [P\None()]
      , 'S[]' => [P\Seq::of()]
      , 'ArrayItterator[]' => [new \ArrayIterator([])]
    ];
  }

  public function callbackProvider()
  {
    return [
      'array callback' => [
        [1, 2], 1, 2, 1
      ]
      , 'traversable callback' => [
        new \ArrayIterator([1, 2]), 1, 2, 1
      ]
      , 'natural interface callback' => [
        P\Seq::of(1, 2), 1, 2, 1
      ]
    ];
  }

  public function scenarioProvider()
  {
    $add = function ($a, $b) {
      return $a + $b;
    };
    return [
      'add 1' => [
        [1]
        , $add
        , 1
      ]
      , 'add S[1]' => [
        P\Seq::of(1)
        , $add
        , 1
      ]

      , 'add ArrayObject[1]' => [
        new \ArrayIterator([1])
        , $add
        , 1
      ]
      , 'add Some(2)' => [
        P\Some(2)
        , $add
        , 2
      ]
      , 'add 1+2+3' => [
        [1, 2, 3]
        , $add
        , 6
      ]
      , 'add S[1,2,3]' => [
        P\Seq::of(1, 2, 3)
        , $add
        , 6
      ]

      , 'add ArrayObject[1,2,3]' => [
        new \ArrayIterator([1, 2, 3])
        , $add
        , 6
      ]
    ];
  }
}

class mapTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\map'
      , P\map
      , 'Ensure the constant is assigned to its function name'
    );
    $this->assertTrue(
      function_exists(P\map)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    P\map(function () use ($arrayLike, $expVal, $expKey) {
      $this->assertTrue(
        3 === func_num_args()
        , 'map callback should receive three arguments'
      );
      $this->assertEquals(
        $expVal
        , func_get_arg(0)
        , 'map callback $value should be equal to the value expected'
      );
      $this->assertEquals(
        $expKey
        , func_get_arg(1)
        , 'map callback $key should be defined'
      );
      if (is_object($arrayLike)) {
        $this->assertTrue(
          $arrayLike === func_get_arg(2)
          , 'map callback $container should be the same instance as the source data being mapped'
        );
      } else {
        $this->assertEquals(
          $arrayLike
          , func_get_arg(2)
          , 'map callback $container should equal to the array being mapped'
        );
      }
    }, $arrayLike);
  }

  public function test_return($array = [1, 2, 3])
  {

    $this->assertInstanceOf(
      Closure
      , P\map(P\I)
      , 'map when partially applied should return a closure'
    );
    $result = P\map(P\I)->__invoke($array);
    $this->assertEquals(
      $array
      , $result
      , 'map applied with idiot should produce a functionally identical array'
    );
    $result[0] += 1;
    $this->assertNotEquals(
      $array
      , $result
      , 'map applied with idiot should not actually be the same instance of array'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $hof, $expected)
  {
    $this->assertEquals($expected
      , P\map($hof, $arrayLike)
      , 'map on array like should have the expected resultant'
    );
  }

  public function callbackProvider()
  {
    return [
      '[1]' => [
        [1], 1, 0
      ]
      , 'S[1]' => [
        P\Seq::of(1), 1, 0
      ]
      , 'Some(1)' => [
        P\Some::of(1), 1, 0
      ]
      , 'ArrayItterator[1]' => [
        new \ArrayIterator([1]), 1, 0
      ]
    ];
  }

  public function scenarioProvider()
  {
    $x2 = function ($value) {
      return $value * 2;
    };
    return [
      '[1,2] * 2' => [
        [1, 2]
        , $x2
        , [2, 4]
      ]
      , 'ArrayIterator[1,2] * 2' => [
        new \ArrayIterator([1, 2])
        , $x2
        , [2, 4]
      ]
      , 'S[1,2] * 2' => [
        P\Seq::of(1, 2)
        , $x2
        , P\Seq::of(2, 4)
      ]
      , 'Some(1) *2' => [
        P\Some(1)
        , $x2
        , P\Some(2)
      ]
      , 'None * 2' => [
        P\None()
        , $x2
        , P\None()
      ]
      , '[1,2,3] to string' => [
        [1, 2, 3]
        , function ($value, $key) {
          return "$key => $value";
        }
        , ['0 => 1', '1 => 2', '2 => 3']
      ]
    ];
  }
}

class callWithTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\callWith'
      , P\callWith
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\callWith)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider returnProvider
   */
  public function test_return($container)
  {
    $this->assertInstanceOf(
      Closure
      , P\callWith('')
      , 'callWith partially applied should be a closure'
    );
    $this->assertInstanceOf(
      Closure
      , P\callWith('getArgs')->__invoke($container)
      , 'callWith when fully applied should be a closure'
    );
    $this->assertEquals(
      [1, 2, 3]
      , P\callWith('getArgs', $container)->__invoke(1, 2, 3)
      , 'callWith should invoke the function with the returned closure'
    );
    $this->assertEquals(
      4,
      P\callWith('countArgs')->__invoke($container)->__invoke(1, 2, 3, 4)
      , 'callWith when partially applied should invoke the function with the returned closure'
    );
  }

  /**
   * @expectedException  \InvalidArgumentException
   * @dataProvider returnProvider
   */
  public function test_contract_broken($container)
  {
    P\callWith('404', $container)->__invoke(1, 2, 3, 4);
  }

  public function returnProvider()
  {
    return [
      'Object' => [new TestClass()]
      , 'Array' => [[
        'getArgs' => function () {
          return func_get_args();
        }
        , 'countArgs' => function () {
          return func_num_args();
        }
      ]]
    ];
  }
}

class pluckObjectWith extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pluckObjectWith'
      , P\pluckObjectWith
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\pluckObjectWith)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @param $object
   * @dataProvider returnProvider
   */
  public function test_return($object)
  {
    $this->assertInstanceOf(
      Closure
      , P\pluckObjectWith('')
      , 'pluckObjectWith should be able to be partially applied'
    );
    $this->assertTrue(
      P\pluckObjectWith('value')->__invoke($object)
      , 'pluckObjectWith\'s yielded closure should retrieve the value of the property on object when applied'
    );
  }

  public function returnProvider()
  {
    return [[new TestClass()]];
  }
}

class pluckWithArrayTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pluckArrayWith'
      , P\pluckArrayWith
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\pluckArrayWith)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @param $array
   * @dataProvider returnProvider
   */
  public function test_return($array)
  {
    $this->assertInstanceOf(
      Closure
      , P\pluckArrayWith('')
      , 'pluckArrayWith should be able to be partially applied'
    );
    $this->assertEquals(
      $array[0]
      , P\pluckArrayWith(0)->__invoke($array)
      , 'pluckArrayWith\'s yielded closure should retrieve the value of the property on object when applied'
    );
  }

  public function returnProvider()
  {
    return [[[1, 2, 3]]];
  }
}

/**
 * Class TestClass
 * @package tests\PHPixme
 * A class to assist in testing properties of object functions
 */
class TestClass
{
  public $value = true;

  public function getArgs()
  {
    return func_get_args();
  }

  public function countArgs()
  {
    return func_num_args();
  }
}