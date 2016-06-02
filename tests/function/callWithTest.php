<?php
namespace tests\PHPixme;

use PHPixme as P;

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
   * @dataProvider returnProvider
   */
  public function test_contract_broken($container)
  {
    $this->expectException(P\exception\InvalidCompositionException::class);
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
