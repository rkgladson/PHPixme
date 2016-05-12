<?php
namespace tests\PHPixme;

use PHPixme as P;

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
