<?php

namespace App\TelegramBot\Types;

use App\TelegramBot\BaseType;
use App\TelegramBot\TypeInterface;

/**
 * Class PollOption
 * This object contains information about one answer option in a poll.
 */
class PollOption extends BaseType implements TypeInterface
{
  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $requiredParams = ['text', 'voter_count'];

  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $map = [
    'text' => true,
    'voter_count' => true,
  ];

  /**
   * Option text, 1-100 characters.
   *
   * @var string
   */
  protected $text;

  /**
   * Number of users that voted for this option.
   *
   * @var int
   */
  protected $voterCount;

  /**
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * @param string $text
   */
  public function setText($text)
  {
    $this->text = $text;
  }

  /**
   * @return int
   */
  public function getVoterCount()
  {
    return $this->voterCount;
  }

  /**
   * @param int $voterCount
   */
  public function setVoterCount($voterCount)
  {
    $this->voterCount = $voterCount;
  }
}
