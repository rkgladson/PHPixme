<?php
namespace tests\PHPixme;

use PHPixme as P;

class tossTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\toss'
      , P\toss
      , 'Ensure the constant is assigned to the function name'
    );

    $this->assertTrue(
      function_exists(P\toss)
      , 'Ensure the constant points to an existing function.'
    );

  }

  /**
   * @dataProvider throwProvider
   */
  public function test_throw($data, $class)
  {
    $this->setExpectedException($class);
    P\toss($data);
  }

  public function throwProvider()
  {
    return [
      'Not exception' => [5, P\Pot::class]
      , 'Pot' => [P\Pot(5), P\Pot::class]
      , 'Exception' => [new \Exception('5'), \Exception::class]
    ];
  }

}
