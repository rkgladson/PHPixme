<?php
namespace tests\PHPixme;

use PHPixme as P;

/**
 * Class walkTest
 * @package tests\PHPixme
 */
class walkTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @coversNothing
   */
  public function test_constant()
  {
    self::assertStringEndsWith('\walk', P\walk);
    self::assertTrue(function_exists(P\walk));
  }

  /**
   * @coversNothing
   * @dataProvider callbackProvider
   */
  public function test_callback($arrayLike, $expVal, $expKey)
  {
    $ran = 0;
    P\walk(function () use ($arrayLike, $expVal, $expKey, &$ran) {
      self::assertEquals(3, func_num_args());
      list($v, $k, $c) = func_get_args();

      self::assertSame($expVal, $v);
      self::assertSame($expKey, $k);
      self::assertSame($arrayLike, $c);

      $ran += 1;
    }, $arrayLike);
    self::assertTrue($ran > 0, 'the callback should of ran');
  }

  /**
   * todo: figure out how to get phpunit to cover the closure only
   */
  public function test_return()
  {
    $array = [1, 2, 3];
    $jai = new JustAnIterator($array);
    $seq = new P\Seq($array);
    $subject = P\walk(noop);

    self::assertInstanceOf(\Closure::class, $subject);
    self::assertEquals($seq, $subject($seq));
    self::assertEquals($array, $subject($array));
    self::assertEquals($jai, $subject($jai));
  }

  /**
   * @coversNothing
   */
  public function test_iterator_immutability()
  {
    $test = new JustAnIterator([1, 2, 3, 4, 5]);
    $test->next();
    $prevKey = $test->key();
    P\walk(identity, $test);
    self::assertEquals($prevKey, $test->key());
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
}
