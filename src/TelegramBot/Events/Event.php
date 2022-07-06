<?php

namespace App\TelegramBot\Events;

use App\TelegramBot\Types\Update;

class Event
{
  /**
   * @var \Closure
   */
  protected $checker;

  /**
   * @var \Closure
   */
  protected $action;

  /**
   * Event constructor.
   *
   * @param \Closure|null $checker
   */
  public function __construct(\Closure $action, \Closure $checker)
  {
    $this->action = $action;
    $this->checker = $checker;
  }

  /**
   * @return \Closure
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * @return \Closure|null
   */
  public function getChecker()
  {
    return $this->checker;
  }

  /**
   * @param \App\TelegramBot\Types\Update
   *
   * @return mixed
   */
  public function executeChecker(Update $message)
  {
    if (is_callable($this->checker)) {
      return call_user_func($this->checker, $message);
    }

    return false;
  }

  /**
   * @param \App\TelegramBot\Types\Update
   *
   * @return mixed
   */
  public function executeAction(Update $message)
  {
    if (is_callable($this->action)) {
      return call_user_func($this->action, $message);
    }

    return false;
  }
}
