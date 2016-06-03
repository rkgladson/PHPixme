<?php
namespace tests\PHPixme;

use PHPixme as P;

/**
 * Class foldRightTest
 * @package tests\PHPixme
 */
class foldRightTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_constant()
  {
    self::assertStringEndsWith('\foldRight', P\foldRight);
    self::assertTrue(function_exists(P\foldRight));
  }

  /**
   * @coversNothing
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    $ran = 0;
    $startVal = 1;
    P\foldRight(
      function () use ($startVal, $arrayLike, $expVal, $expKey, &$ran) {
        self::assertEquals(4, func_num_args());
        list($s, $v, $k, $c) = func_get_args();

        self::assertSame($startVal, $s);
        self::assertSame($expVal, $v);
        self::assertEquals($expKey, $k);
        self::assertSame($arrayLike, $c);

        $ran += 1;
        return $s;
      }
      , $startVal
      , $arrayLike
    );
    self::assertGreaterThan(0, $ran);
  }

  /**
   * @coversNothing
   */
  public function test_return($expected = 1, $array = ['a', 'b', 'c', 'd'])
  {
    $subject = P\foldRight(identity, $expected);

    self::assertInstanceOf(\Closure::class, $subject);
    self::assertEquals($expected, $subject([]));
    self::assertEquals($expected, $subject($array));
  }

  /**
   * @coversNothing
   */
  public function test_iterator_immutability()
  {
    $test = new JustAnIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\foldRight(identity, 0, $test);
    self::assertEquals($prevKey, $test->key());
  }

  /**
   * @coversNothing
   * @dataProvider orderProvider
   */
  public function test_order($source, $expected)
  {
    $concat = function ($x, $y) {
      return $x . $y;
    };
    self::assertEquals($expected, P\foldRight($concat, '', $source));
  }

  /**
   * todo: have php unit only cover the closure, not the function...
   * @dataProvider scenarioProvider
   */
  public function test_scenario($arrayLike, $startVal, $action, $expected)
  {
    self::assertEquals($expected, P\foldRight($action, $startVal, $arrayLike));
  }

  public function callbackProvider()
  {
    $source = [1];
    return [
      'array callback' => [$source, $source[0], 0]
      , 'iterator aggregate callback' => [new \ArrayObject($source), $source[0], 0]
      , 'iterator callback' => [new \ArrayIterator($source), $source[0], 0]
      , 'natural interface callback' => [P\Seq($source), $source[0], 0]
      , 'just an iterator' => [new JustAnIterator($source), $source[0], 0]
    ];
  }

  public function orderProvider()
  {
    $source = [1, 2, 3];
    $expected = '321';
    return [
      '[1,2,3]' => [$source, $expected]
      , 'ArrayObject([1,2,3])' => [new \ArrayObject($source), $expected]
      , 'ArrayIterator([1,2,3])' => [new \ArrayIterator($source), $expected]
      , 'JAI([1,2,3])' => [new JustAnIterator($source), $expected]
      , 'Seq(1,2,3)' => [P\Seq($source), $expected]
    ];
  }

  public function scenarioProvider()
  {
    $add = function ($a, $b) {
      return $a + $b;
    };
    return [
      'add simple empty array' => [[], 0, $add, 0]
      , 'add simple S[]' => [P\Seq::of(), 0, $add, 0]
      , 'add simple None' => [P\None(), 0, $add, 0]
      , 'ArrayObject[]' => [new \ArrayIterator([]), 0, $add, 0]
      , 'add 1+2+3' => [[1, 2, 3], 0, $add, 6]
      , 'add S[1,2,3]' => [P\Seq::of(1, 2, 3), 0, $add, 6]
      , 'Some(2)+2' => [P\Some(2), 2, $add, 4]
      , 'add ArrayObject[1,2,3]' => [new \ArrayIterator([1, 2, 3]), 0, $add, 6]
      , 'add JustAnIterator[1,2,3]' => [new JustAnIterator([1, 2, 3]), 0, $add, 6]
    ];
  }


}
