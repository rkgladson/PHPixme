<?php
namespace tests\PHPixme;

use PHPixme as P;

class pluckWithArrayTest extends \PHPUnit_Framework_TestCase
{
  public function test_constant()
  {
    $this->assertStringEndsWith(
      '\pluckArrayWith'
      , P\pluckArrayWith
      , 'Ensure the constant is assigned to the function name'
    );
    $this->assertTrue(
      function_exists(P\pluckArrayWith)
      , 'Ensure the constant points to an existing function.'
    );
  }

  /**
   * @param $array
   * @dataProvider returnProvider
   */
  public function test_return($array)
  {
    $this->assertInstanceOf(
      Closure
      , P\pluckArrayWith('')
      , 'pluckArrayWith should be able to be partially applied'
    );
    $this->assertEquals(
      $array[0]
      , P\pluckArrayWith(0)->__invoke($array)
      , 'pluckArrayWith\'s yielded closure should retrieve the value of the property on object when applied'
    );
  }

  public function returnProvider()
  {
    return [[[1, 2, 3]]];
  }
}
