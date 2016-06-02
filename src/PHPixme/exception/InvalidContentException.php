<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 6/1/2016
 * Time: 5:06 PM
 */

namespace PHPixme\exception;


use PHPixme\ClosedTrait;
use PHPixme\ImmutableConstructorTrait;
use PHPixme\Pot;

/**
 * Class InvalidContentException
 * Represents the error of the contents (or lack there of) of a container being inappropriate for
 * the action taken.
 * @package PHPixme\exception
 */
class InvalidContentException extends \Exception implements CollectibleExceptionInterface
{
  use ImmutableConstructorTrait, ClosedTrait;
  private $content;

  /**
   * InvalidContentsException constructor.
   * @param mixed $content
   * @param string $message
   * @param int $code
   * @param \Exception $previous
   */
  public function __construct($content, $message = '', $code = 0, \Exception $previous = null)
  {
    $this->assertOnce();
    parent::__construct($message, $code, $previous);
    $this->content = $content;
  }

  /**
   * Returns the inappropriate content
   * @return mixed
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * @inheritdoc
   */
  public function toPot()
  {
   return new Pot($this->content, $this->message, $this->code, $this);
  }
}