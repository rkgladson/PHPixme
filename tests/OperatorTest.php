<?php
namespace tests\PHPixme;

use PHPixme as P;
defined('Closure') or define('Closure', \Closure::class);

class addTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\add'
      , P\add
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\add)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\add()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x + $y;
    $this->assertEquals(
      $expectedResult
      , P\add($x, $y)
      , 'Immediate application'
    );
    $add_XXY = P\add(P\_(), $y);
    $this->assertInstanceOf(Closure, $add_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $add_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class subTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\sub'
      , P\sub
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\sub)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\sub()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x - $y;
    $this->assertEquals(
      $expectedResult
      , P\sub($x, $y)
      , 'Immediate application'
    );
    $sub_XXY = P\sub(P\_(), $y);
    $this->assertInstanceOf(Closure, $sub_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $sub_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class mulTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\mul'
      , P\mul
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\mul)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\mul()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x * $y;
    $this->assertEquals(
      $expectedResult
      , P\mul($x, $y)
      , 'Immediate application'
    );
    $mul_XXY = P\mul(P\_(), $y);
    $this->assertInstanceOf(Closure, $mul_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $mul_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class divTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\div'
      , P\div
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\div)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\div()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x / $y;
    $this->assertEquals(
      $expectedResult
      , P\div($x, $y)
      , 'Immediate application'
    );
    $div_XXY = P\div(P\_(), $y);
    $this->assertInstanceOf(Closure, $div_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $div_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class modTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\mod'
      , P\mod
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\mod)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\mod()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x % $y;
    $this->assertEquals(
      $expectedResult
      , P\mod($x, $y)
      , 'Immediate application'
    );
    $mod_XXY = P\mod(P\_(), $y);
    $this->assertInstanceOf(Closure, $mod_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $mod_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class powTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pow'
      , P\pow
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\pow)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\pow()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1, $y = 2)
  {
    $expectedResult = $x ** $y;
    $this->assertEquals(
      $expectedResult
      , P\pow($x, $y)
      , 'Immediate application'
    );
    $pow_XXY = P\pow(P\_(), $y);
    $this->assertInstanceOf(Closure, $pow_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $pow_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class catTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\cat'
      , P\cat
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\cat)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\cat()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = '1', $y = '2')
  {
    $expectedResult = $x . $y;
    $this->assertEquals(
      $expectedResult
      , P\cat($x, $y)
      , 'Immediate application'
    );
    $cat_XXY = P\cat(P\_(), $y);
    $this->assertInstanceOf(Closure, $cat_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $cat_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class appTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\app'
      , P\app
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\app)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\app()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = [1, 2, 3], $y = [4, 5, 6])
  {
    $expectedResult = $x + $y;
    $this->assertEquals(
      $expectedResult
      , P\app($x, $y)
      , 'Immediate application'
    );
    $app_XXY = P\app(P\_(), $y);
    $this->assertInstanceOf(Closure, $app_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $app_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class negTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\neg'
      , P\neg
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\neg)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\neg()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 1)
  {
    $expectedResult = -$x;
    $this->assertEquals(
      $expectedResult
      , P\neg($x)
      , 'Immediate application'
    );
    $neg_X = P\neg(P\_());
    $this->assertInstanceOf(Closure, $neg_X, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $neg_X($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class eqTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\eq'
      , P\eq
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\eq)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\eq()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = 0)
  {
    $expectedResult = $x == $y;
    $this->assertEquals(
      $expectedResult
      , P\eq($x, $y)
      , 'Immediate application'
    );
    $eq_XXY = P\eq(P\_(), $y);
    $this->assertInstanceOf(Closure, $eq_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $eq_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class idTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\id'
      , P\id
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\id)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\id()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = '')
  {
    $expectedResult = $x === $y;
    $this->assertEquals(
      $expectedResult
      , P\id($x, $y)
      , 'Immediate application'
    );
    $id_XXY = P\id(P\_(), $y);
    $this->assertInstanceOf(Closure, $id_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $id_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class neqTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\neq'
      , P\neq
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\neq)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\neq()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = 0)
  {
    $expectedResult = $x != $y;
    $this->assertEquals(
      $expectedResult
      , P\neq($x, $y)
      , 'Immediate application'
    );
    $neq_XXY = P\neq(P\_(), $y);
    $this->assertInstanceOf(Closure, $neq_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $neq_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class nidTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\nid'
      , P\nid
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\nid)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\nid()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = false, $y = '')
  {
    $expectedResult = $x !== $y;
    $this->assertEquals(
      $expectedResult
      , P\nid($x, $y)
      , 'Immediate application'
    );
    $nid_XXY = P\nid(P\_(), $y);
    $this->assertInstanceOf(Closure, $nid_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $nid_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class gtTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\gt'
      , P\gt
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\gt)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\gt()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 0, $y = 1)
  {
    $expectedResult = $x > $y;
    $this->assertEquals(
      $expectedResult
      , P\gt($x, $y)
      , 'Immediate application'
    );
    $gt_XXY = P\gt(P\_(), $y);
    $this->assertInstanceOf(Closure, $gt_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $gt_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class gteTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\gte'
      , P\gte
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\gte)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\gte()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 0, $y = 1)
  {
    $expectedResult = $x >= $y;
    $this->assertEquals(
      $expectedResult
      , P\gte($x, $y)
      , 'Immediate application'
    );
    $gte_XXY = P\gte(P\_(), $y);
    $this->assertInstanceOf(Closure, $gte_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $gte_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class ltTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\lt'
      , P\lt
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\lt)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\lt()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($x = 0, $y = 1)
  {
    $expectedResult = $x < $y;
    $this->assertEquals(
      $expectedResult
      , P\lt($x, $y)
      , 'Immediate application'
    );
    $lt_XXY = P\lt(P\_(), $y);
    $this->assertInstanceOf(Closure, $lt_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $lt_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class lteTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\lte'
      , P\lte
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\lte)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\lte()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($lhs = 0, $rhs = 1)
  {
    $expectedResult = $lhs <= $rhs;
    $this->assertEquals(
      $expectedResult
      , P\lte($lhs, $rhs)
      , 'Immediate application'
    );
    $lte_XXY = P\lte(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $lte_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $lte_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class ufoTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\ufo'
      , P\ufo
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\ufo)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\ufo()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider returnProvider
   */
  public function test_return($lhs = 0, $rhs = 1)
  {

//    $expectedResult = $x <=> $y;
    $expectedResult = $lhs > $rhs ? 1 : ($lhs < $rhs ? -1 : 0);

    $this->assertEquals(
      $expectedResult
      , P\ufo($lhs, $rhs)
      , 'Immediate application'
    );
    $this->assertTrue(is_int(P\ufo($lhs, $rhs)));
    $ufo_XXY = P\ufo(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $ufo_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $ufo_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
    $this->assertTrue(is_int($ufo_XXY($lhs)));
  }

  public function returnProvider()
  {
    return [
      ['', 1]
      , ['1', 0]
      , ['0', 1]
      , [1.0, 1]
      , ['a', 'aa']
    ];
  }
}

class andLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\andL'
      , P\andL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\andL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\andL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x && $y;
    $this->assertEquals(
      $expectedResult
      , P\andL($x, $y)
      , 'Immediate application'
    );
    $andL_XXY = P\andL(P\_(), $y);
    $this->assertInstanceOf(Closure, $andL_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $andL_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [true, true]
      , [true, false]
      , [false, true]
      , [false, false]
    ];
  }
}

class orLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\orL'
      , P\orL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\orL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\orL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x || $y;
    $this->assertEquals(
      $expectedResult
      , P\orL($x, $y)
      , 'Immediate application'
    );
    $orL_XXY = P\orL(P\_(), $y);
    $this->assertInstanceOf(Closure, $orL_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $orL_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [true, true]
      , [true, false]
      , [false, true]
      , [false, false]
    ];
  }
}

class xorLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\xorL'
      , P\xorL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\xorL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\xorL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = ($x xor $y);

    $this->assertEquals(
      $expectedResult
      , P\xorL($x, $y)
      , 'Immediate application'
    );
    $xorL_XXY = P\xorL(P\_(), $y);
    $this->assertInstanceOf(Closure, $xorL_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $xorL_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [true, true]
      , [true, false]
      , [false, true]
      , [false, false]
    ];
  }
}

class notLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\notL'
      , P\notL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\notL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\notL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return()
  {
    $this->assertTrue(P\notL(false));
    $this->assertFalse(P\notL(true));
    $this->assertTrue(P\notL(P\_())->__invoke(false));
    $this->assertFalse(P\notL(P\_())->__invoke(true));

  }
}

class andBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\andB'
      , P\andB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\andB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\andB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x && $y;
    $this->assertEquals(
      $expectedResult
      , P\andB($x, $y)
      , 'Immediate application'
    );
    $andB_XXY = P\andB(P\_(), $y);
    $this->assertInstanceOf(Closure, $andB_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $andB_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [1, 1]
      , [1, 0]
      , [0, 1]
      , [0, 0]
    ];
  }
}

class orBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\orB'
      , P\orB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\orB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\orB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = $x || $y;
    $this->assertEquals(
      $expectedResult
      , P\orB($x, $y)
      , 'Immediate application'
    );
    $orB_XXY = P\orB(P\_(), $y);
    $this->assertInstanceOf(Closure, $orB_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $orB_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [1, 1]
      , [1, 0]
      , [0, 1]
      , [0, 0]
    ];
  }
}

class xorBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\xorB'
      , P\xorB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\xorB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\xorB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  /**
   * @dataProvider truthTableProvider
   */
  public function test_return($x = true, $y = false)
  {
    $expectedResult = ($x xor $y);

    $this->assertEquals(
      $expectedResult
      , P\xorB($x, $y)
      , 'Immediate application'
    );
    $xorB_XXY = P\xorB(P\_(), $y);
    $this->assertInstanceOf(Closure, $xorB_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $xorB_XXY($x)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }

  public function truthTableProvider()
  {
    return [
      [1, 1]
      , [1, 0]
      , [0, 1]
      , [0, 0]
    ];
  }
}

class notBTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\notB'
      , P\notB
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\notB)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\notB()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return()
  {
    $resultFalse = ~1;
    $resultTrue = ~0;
    $this->assertEquals($resultTrue, P\notB(0));
    $this->assertEquals($resultFalse, P\notB(1));
    $this->assertEquals($resultTrue, P\notB(P\_())->__invoke(0));
    $this->assertEquals($resultFalse, P\notB(P\_())->__invoke(1));

  }
}

class shiftLTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\shiftL'
      , P\shiftL
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\shiftL)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\shiftL()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($lhs = 1, $rhs = 5)
  {
    $expectedResult = $lhs << $rhs;
    $this->assertEquals(
      $expectedResult
      , P\shiftL($lhs, $rhs)
      , 'Immediate application'
    );
    $shiftL_XXY = P\shiftL(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $shiftL_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $shiftL_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}

class shiftRTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\shiftR'
      , P\shiftR
      , 'assure the constant contains itself'
    );
    $this->assertTrue(
      function_exists(P\shiftR)
      , 'assure the constant points to the function by the same name'
    );
    $this->assertInstanceOf(
      Closure,
      P\shiftR()
      , 'the function applied with no arguments should result in the curried function'
    );
  }

  public function test_return($lhs = 1, $rhs = 5)
  {
    $expectedResult = $lhs >> $rhs;
    $this->assertEquals(
      $expectedResult
      , P\shiftR($lhs, $rhs)
      , 'Immediate application'
    );
    $shiftR_XXY = P\shiftR(P\_(), $rhs);
    $this->assertInstanceOf(Closure, $shiftR_XXY, 'deferred flipped application');
    $this->assertEquals(
      $expectedResult
      , $shiftR_XXY($lhs)
      , 'the function should be able to be placeheld for easier flipping'
    );
  }
}