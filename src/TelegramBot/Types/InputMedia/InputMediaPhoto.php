<?php

namespace App\TelegramBot\Types\InputMedia;

/**
 * Class InputMediaPhoto
 * Represents a photo to be sent.
 */
class InputMediaPhoto extends InputMedia
{
  /**
   * InputMediaPhoto constructor.
   *
   * @param string $media
   * @param null   $caption
   * @param null   $parseMode
   */
  public function __construct($media, $caption = null, $parseMode = null)
  {
    $this->type = 'photo';
    $this->media = $media;
    $this->caption = $caption;
    $this->parseMode = $parseMode;
  }
}
