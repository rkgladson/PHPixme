<?php
namespace tests\PHPixme;

use PHPixme as P;

class YTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\Y'
      , P\Y
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\Y)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @dataProvider returnProvider
   */
  public function test_return(\Closure $containerFn, array $input, $output)
  {
    $recursive = P\Y($containerFn);
    $this->assertInstanceOf(
      Closure
      , $recursive
      , 'Should retrun a closure'
    );
    $this->assertEquals(
      $output
      , call_user_func_array($recursive, $input)
    );
  }

  public function returnProvider()
  {
    return [
      'factorial 10' => [
        function ($factorial) {
          return function ($n) use ($factorial) {
            return ($n <= 1)
              ? 1
              : $factorial($n - 1) * $n;
          };
        }
        , [10]
        , 3628800
      ]
      , 'fibonacci 10' => [
        function ($fibbinacci) {
          return function ($n) use ($fibbinacci) {
            return ($n <= 1)
              ? $n
              : ($fibbinacci($n - 1) + $fibbinacci($n - 2));
          };
        }
        , [10]
        , 55
      ]
    ];
  }
}
