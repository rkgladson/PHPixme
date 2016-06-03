<?php

namespace PHPixme\exception;

use PHPixme\ClosedTrait;
use PHPixme\ImmutableConstructorTrait;
use PHPixme\Pot;

/**
 * Class InvalidCompositionException
 * Represents that the outcome of the inputs did not form the value expected by the contract
 * @package PHPixme\exception
 */
class InvalidCompositionException extends \Exception implements CollectibleExceptionInterface
{
  use ImmutableConstructorTrait, ClosedTrait;
  private $composition;

  /**
   * InvalidCompositionException constructor.
   * @param mixed $composition the composition that caused this error
   * @param string $message
   * @param int $code
   * @param \Exception|null $previous
   */
  public function __construct($composition, $message = '', $code = 0, \Exception $previous = null)
  {
    $this->assertOnce();
    parent::__construct($message, $code, $previous);
    $this->composition = $composition;
  }

  /**
   * @return mixed
   */
  public function get() {
    return $this->composition;
  }

  /** @inheritdoc */
  public function toPot()
  {
    return new Pot($this->composition, $this->message, $this->code, $this);
  }

}