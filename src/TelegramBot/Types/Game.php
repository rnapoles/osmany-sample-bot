<?php

namespace App\TelegramBot\Types;

use App\TelegramBot\BaseType;
use App\TelegramBot\TypeInterface;

/**
 * Game.
 **/
class Game extends BaseType implements TypeInterface
{
  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $requiredParams = ['title', 'description', 'photo'];
  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $map = [
    'title' => true,
    'description' => true,
    'photo' => ArrayOfPhotoSize::class,
    'text' => true,
    'text_entities' => ArrayOfMessageEntity::class,
    'animation' => Animation::class,
  ];

  /**
   * Title of the game.
   *
   * @var string
   */
  protected $title;

  /**
   * Description of the game.
   *
   * @var string
   */
  protected $description;

  /**
   * Photo that will be displayed in the game message in chats.
   * array of \App\TelegramBot\Types\Photo.
   *
   * @var array
   */
  protected $photo;

  /**
   * Optional. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls [setGameScore](https://core.telegram.org/bots/api/#setgamescore), or manually edited using [editMessageText](https://core.telegram.org/bots/api/#editmessagetext). 0-4096 characters.
   *
   * @var string
   */
  protected $text;

  /**
   * Optional. Special entities that appear in *text*, such as usernames, URLs, bot commands, etc.
   * array of \App\TelegramBot\Types\MessageEntity.
   *
   * @var array
   */
  protected $textEntities;

  /**
   * Optional. Information about the animation.
   *
   * @var \App\TelegramBot\Types\Animation
   */
  protected $animation;

  public function setTitle(string $title)
  {
    if ($this->title != $title) {
      $this->title = $title;
    }
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setDescription(string $description)
  {
    if ($this->description != $description) {
      $this->description = $description;
    }
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setPhoto(array $photo)
  {
    if ($this->photo != $photo) {
      $this->photo = $photo;
    }
  }

  public function getPhoto(): array
  {
    return $this->photo;
  }

  public function setText(string $text)
  {
    if ($this->text != $text) {
      $this->text = $text;
    }
  }

  public function getText(): string
  {
    return $this->text;
  }

  /**
   * @param array $textEntities
   */
  public function setTextEntities($textEntities)
  {
    if ($this->textEntities != $textEntities) {
      $this->textEntities = $textEntities;
    }
  }

  public function getTextEntities(): ArrayOfMessageEntity
  {
    return $this->textEntities;
  }

  public function setAnimation(Animation $animation)
  {
    if ($this->animation != $animation) {
      $this->animation = $animation;
    }
  }

  public function getAnimation(): Animation
  {
    return $this->animation;
  }
}
