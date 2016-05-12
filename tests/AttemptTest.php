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
      function_exists(P\Attempt::class)
      , 'The companion function exists for the class.'
    );
  }

  public function test_Attempt_companion_returns_children()
  {
    $this->assertInstanceOf(
      P\Success::class
      , P\Attempt(function () {
      })
      , "No thrown values produce a Success"
    );
    $this->assertInstanceOf(
      P\Failure::class
      , P\Attempt(function () {
        throw new \Exception();
      })
      , 'Throwing an exception produces a Failure'
    );
  }

  public function test_Attempt_of_returns_children()
  {
    $this->assertInstanceOf(
      P\Success::class
      , P\Attempt::of(function () {
      })
      , "No thrown values produce a Success"
    );
    $this->assertInstanceOf(
      P\Failure::class
      , P\Attempt::of(function () {
        throw new \Exception();
      })
      , 'Throwing an exception produces a Failure'
    );
  }
}
