<?php
namespace PHPixme\exception;
use PHPixme\ClosedTrait;
use PHPixme\ImmutableConstructorTrait;
use PHPixme\Pot;

/**
 * Class InvalidReturnException
 * Thrown when a callback function returns a invalid return type or violates a constraint
 * @package PHPixme\exception
 */
class InvalidReturnException extends \UnexpectedValueException implements CollectibleExceptionInterface
{
  use ImmutableConstructorTrait, ClosedTrait;
  private $return;

  /**
   * InvalidReturnException constructor.
   * @param mixed $return
   * @param string $message
   * @param int $code
   * @param \Exception $previous
   */
  public function __construct($return, $message = '', $code = 0, \Exception $previous = null)
  {
    $this->assertOnce();
    parent::__construct($message, $code, $previous);
    $this->return = $return;
  }
  
  /**
   * @return string
   */
  public function get()
  {
    return $this->return;
  }
  
  /** @inheritdoc */
  public function toPot()
  {
    return new Pot($this->return, $this->message, $this->code, $this);
  }
}