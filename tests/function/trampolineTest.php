<?php
namespace tests\PHPixme;

use PHPixme as P;

class trampolineTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\trampoline'
      , P\trampoline
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\trampoline)
      , 'Ensure the constant points to an existing function.'
    );
  }

  public function test_return($value = 10)
  {
    $springyConstant = P\trampoline(P\K);
    $this->assertInstanceOf(
      Closure
      , $springyConstant
      , 'should return a decorated closure'
    );
    $this->assertTrue(
      $value === $springyConstant($value)
      , 'when applied to Kestrel, it should eventually return the value it was given initally'
    );
  }

  /**
   * @dataProvider scenarioProvider
   */
  public function test_scenaorio($function, $input, $expectedResult)
  {
    $application = P\trampoline($function);
    $this->assertEquals(
      $expectedResult
      , $application($input)
    );
  }

  public function scenarioProvider()
  {
    $factorial = function ($n, $acc = 1) use (&$factorial) {
      return $n > 0
        ? function () use ($n, $acc, $factorial) {
          return $factorial($n - 1, $acc * $n);
        }
        : $acc;
    };
    $thunking = function ($n, $step = null) use (&$thunking) {
      $step = !is_null($step) ? $step : $n;
      return $step > 0
        ? function () use ($thunking, $n, $step) {
          return $thunking($n, $step - 1);
        }
        : $n;
    };
    return [
      'Apprehensive kesterl' => [$thunking, 10, 10]
      , 'factorial 10' => [$factorial, 10, 3628800]
    ];
  }
}
