<?php
namespace tests\PHPixme\exception;

use function tests\PHPixme\getAllTraits;
use PHPixme as P;
use PHPixme\exception\InvalidContentException as exception;

/**
 * Class InvalidContentExceptionTest
 * @package tests\PHPixme\exception
 * @coversDefaultClass PHPixme\exception\InvalidContentException
 */
class InvalidContentExceptionTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @covers ::__construct
   */
  public function test_new() {
    $content = false;
    $previous = new \Exception();
    $message = 'happy birthday';
    $code = 8675309;

    $result = new exception($content, $message, $code, $previous);

    self::assertAttributeSame($content, 'content', $result);
    self::assertEquals($message, $result->getMessage());
    self::assertEquals($code, $result->getCode());
    self::assertSame($previous, $result->getPrevious());
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
   * @dataProvider contentProvider
   */
  public function test_get($return) {
    $subject = new exception($return);

    self::assertSame($return, $subject->get());
  }

  /**
   * @covers ::toPot
   */
  public function test_toPot($content = null, $message = 'Whoops!', $code = 404) {
    $subject = new exception($content, $message, $code);

    $results = $subject->toPot();

    self::assertInstanceOf(P\Pot::class, $results);
    self::assertSame($content, $results->get());
    self::assertSame($message, $results->getMessage());
    self::assertSame($code, $results->getCode());
    self::assertSame($subject, $results->getPrevious());
  }

  public function contentProvider() {
    return [
      'null' => [null]
      , 'stdClass' => [new \stdClass()]
      , 'self' => [new exception(null)]
      , 'array' => [[1,2,3]]
    ];
  }

}