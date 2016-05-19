<?php
namespace tests\PHPixme\exception;

use PHPixme\ClosedTrait;
use PHPixme\exception\MutationException as testSubject;
use function tests\PHPixme\getAllTraits;
class MutationExceptionTest extends \PHPUnit_Framework_TestCase
{
  public function test_properties()
  {
    self::assertInstanceOf(\Exception::class, new testSubject());
  }

  public function test_message()
  {
    self::assertContains(
      (new testSubject('message'))->getMessage()
      , testSubject::quotes
      , 'It should select from one of the quotes'
    );
  }

  public function test_sense_of_humor()
  {
    $this->expectException(testSubject::class);
    (new testSubject())->__construct();
  }

  public function test_closed_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(testSubject::class));
    self::assertTrue(
      false !== array_search(ClosedTrait::class, $traits)
      , 'should be closed'
    );
  }
}
