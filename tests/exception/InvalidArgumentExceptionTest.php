<?php

namespace tests\PHPixme\exception;

use function tests\PHPixme\getAllTraits;
use PHPixme as P;
use PHPixme\exception\InvalidArgumentException as exception;

/**
 * Class InvalidArgumentExceptionTest
 * @package tests\PHPixme\exception
 * @coversDefaultClass PHPixme\exception\InvalidArgumentException
 */
class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers ::__construct
   */
  public function test_new() {
    $argument = new \stdClass();
    $position = 5;
    $previous = new \Exception();
    $message = 'happy birthday';
    $code = 8675309;

    $result = new exception($argument, $position, $message, $code, $previous);
    $defaultResult = new exception($argument);

    self::assertAttributeSame($argument, 'argument', $result);
    self::assertAttributeEquals($position, 'position', $result);
    self::assertEquals($message, $result->getMessage());
    self::assertEquals($code, $result->getCode());
    self::assertSame($previous, $result->getPrevious());

    self::assertAttributeEquals(0, 'position', $defaultResult);
  }

  /**
   * @coversNothing
   */
  public function test_trait()
  {
    $traits = getAllTraits(new \ReflectionClass(exception::class));

    self::assertContains(P\ClosedTrait::class, $traits);
    self::assertContains(P\ImmutableConstructorTrait::class, $traits);
  }

  /**
   * @coversNothing
   */
  public function test_patience() {
    $this->expectException(P\exception\MutationException::class);
    (new exception(1))->__construct(null);
  }

  /**
   * @covers ::get
   * @dataProvider compositionProvider
   */
  public function test_get($return) {
    $subject = new exception($return);

    self::assertSame($return, $subject->get());
  }

  /**
   * @covers ::getPosition
   */
  public function test_getPosition($position = 6) {
    $subject = new exception(null, $position);

    self::assertEquals($position, $subject->getPosition());
  }

  /**
   * @covers ::toPot
   */
  public function test_toPot($arg = null, $position = 5, $message = 'Whoops!', $code = 404) {
    $subject = new exception($arg, $position, $message, $code);

    $results = $subject->toPot();

    self::assertInstanceOf(P\Pot::class, $results);
    self::assertEquals([$position=>$arg], $results->get());
    self::assertSame($message, $results->getMessage());
    self::assertSame($code, $results->getCode());
    self::assertSame($subject, $results->getPrevious());
  }

  public function compositionProvider() {
    return [
      'null' => [null]
      , 'stdClass' => [new \stdClass()]
      , 'self' => [new exception(null)]
      , 'array' => [[1,2,3]]
    ];
  }
}
