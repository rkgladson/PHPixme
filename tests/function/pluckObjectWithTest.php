<?php
namespace tests\PHPixme;

use PHPixme as P;

class pluckObjectWithTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pluckObjectWith'
      , P\pluckObjectWith
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\pluckObjectWith)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @param $object
   * @dataProvider returnProvider
   */
  public function test_return($object)
  {
    $this->assertInstanceOf(
      Closure
      , P\pluckObjectWith('')
      , 'pluckObjectWith should be able to be partially applied'
    );
    $this->assertTrue(
      P\pluckObjectWith('value')->__invoke($object)
      , 'pluckObjectWith\'s yielded closure should retrieve the value of the property on object when applied'
    );
  }

  public function returnProvider()
  {
    return [[new TestClass()]];
  }
}
