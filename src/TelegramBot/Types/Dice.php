<?php

namespace App\TelegramBot\Types;

use App\TelegramBot\BaseType;
use App\TelegramBot\TypeInterface;

/**
 * Class Dice
 * This object represents a dice with random value from 1 to 6. (Yes, we're aware of the “proper” singular of die.
 * But it's awkward, and we decided to help it change. One dice at a time!).
 */
class Dice extends BaseType implements TypeInterface
{
  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $requiredParams = ['value'];

  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $map = [
    'value' => true,
  ];

  /**
   * Value of the dice, 1-6.
   *
   * @var int
   */
  protected $value;

  /**
   * @return int
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * @param int $value
   */
  public function setValue($value)
  {
    $this->value = $value;
  }
}
