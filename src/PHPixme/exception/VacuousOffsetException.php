<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/13/2016
 * Time: 10:23 AM
 */

namespace PHPixme\exception;

use PHPixme\ClosedTrait;
use PHPixme\ImmutableConstructorTrait;
use PHPixme\Pot;
use PHPixme\UnaryApplicativeInterface;

/**
 * Class VacuousOffsetException
 * Represents the contained offset as an error when the position does not exist.
 * @package PHPixme\exception
 */
class VacuousOffsetException extends \OutOfBoundsException
  implements
  UnaryApplicativeInterface
  , CollectibleExceptionInterface
  , \Countable
{
  use ClosedTrait, ImmutableConstructorTrait;
  /**
   * @var mixed
   */
  protected $offset;

  /**
   * VacuousOffsetException constructor.
   * @param mixed $offset The offset that was attempted
   * @param string $message (Optional) A description of why the offset is Vacuous
   * @throws MutationException
   */
  public function __construct($offset, $message = 'The offset does not exist')
  {
    $this->assertOnce();
    $this->offset = $offset;
    $this->message = $message;
  }

  /**
   * Gets the contained offset value which caused the Exception
   * @return mixed
   */
  public function get()
  {
    return $this->offset;
  }

  /**
   * Passes a single item value into a new instance of itself.
   * @param $offset
   * @return static
   */
  public static function of($offset)
  {
    return new static($offset);
  }

  /**
   * @inheritdoc
   */
  public function count()
  {
    return 1;
  }

  /**
   * @inheritdoc
   */
  public function toPot()
  {
    return new Pot($this->offset, $this->message, $this->code, $this);
  }
}