<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 11:58 AM
 */
namespace tests\PHPixme;

use PHPixme as P;

class AttemptTest extends \PHPUnit_Framework_TestCase
{

  public function test_Attempt_constants()
  {
    $this->assertTrue(
      P\Attempt::class === P\Attempt
      , 'The constant for the Class and Function should be equal to the Class Path'
    );
    $this->assertTrue(
      function_exists(P\Attempt)
      , 'The companion function exists for the class.'
    );
  }

  public function test_Attempt_companion_returns_children()
  {
    $this->assertStringEndsWith(
      '\Attempt'
      , P\Attempt
      , 'Ensure constant ends with the function name'
    );

    $this->assertInstanceOf(
      P\Success
      , P\Attempt(function () {
      })
      , "No thrown values produce a Success"
    );
    $this->assertInstanceOf(
      P\Failure
      , P\Attempt(function () {
        throw new \Exception();
      })
      , 'Throwing an exception produces a Failure'
    );
  }
}
