<?php

namespace App\TelegramBot\Events;

use App\TelegramBot\Botan;
use App\TelegramBot\Types\Update;
use Closure;
use ReflectionFunction;

class EventCollection
{
  /**
   * Array of events.
   *
   * @var array
   */
  protected $events;

  /**
   * Botan tracker.
   *
   * @var \App\TelegramBot\Botan
   */
  protected $tracker;

  /**
   * EventCollection constructor.
   *
   * @param string $trackerToken
   */
  public function __construct($trackerToken = null)
  {
    if ($trackerToken) {
      $this->tracker = new Botan($trackerToken);
    }
  }

  /**
   * Add new event to collection.
   *
   * @param Closure|null $checker
   *
   * @return \App\TelegramBot\Events\EventCollection
   */
  public function add(Closure $event, $checker = null)
  {
    $this->events[] = !is_null($checker) ? new Event($event, $checker)
            : new Event(
              $event,
              function () {
              }
            );

    return $this;
  }

  /**
   * @param \App\TelegramBot\Types\Update
   */
  public function handle(Update $update)
  {
    foreach ($this->events as $event) {
      /* @var \App\TelegramBot\Events\Event $event */
      if (true === $event->executeChecker($update)) {
        if (false === $event->executeAction($update)) {
          if (!is_null($this->tracker)) {
            $checker = new ReflectionFunction($event->getChecker());
            $this->tracker->track($update->getMessage(), $checker->getStaticVariables()['name']);
          }
          break;
        }
      }
    }
  }
}
