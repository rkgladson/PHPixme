<?php

namespace PHPixme\exception;

use PHPixme\ClosedTrait;
use PHPixme\ImmutableConstructorTrait;
use PHPixme\Pot;

/**
 * Class InvalidArgumentException
 * Represents a constraint violation of an argument
 * @package PHPixme\exception
 */
class InvalidArgumentException extends \InvalidArgumentException implements CollectibleExceptionInterface
{
  use ImmutableConstructorTrait, ClosedTrait;

  private $position = 0;
  private $argument;

  /**
   * InvalidArgumentException constructor.
   * @param mixed $argument
   * @param int $position
   * @param string $message
   * @param int $code
   * @param \Exception|null $previous
   */
  public function __construct($argument, $position = 0, $message = '', $code = 0, \Exception $previous = null)
  {
    $this->assertOnce();
    parent::__construct($message, $code, $previous);
    $this->argument = $argument;
    $this->position = $position;
  }

  /**
   * Gets the position of the violation
   * @return int
   */
  public function getPosition()
  {
    return $this->position;
  }

  /**
   * Return the violation
   * @return mixed
   */
  public function get()
  {
    return $this->argument;
  }

  /**
   * Creates the pot containing an array of argument at position
   * @return Pot
   */
  public function toPot()
  {
    return new Pot([$this->position => $this->argument], $this->message, $this->code, $this);
  }

}