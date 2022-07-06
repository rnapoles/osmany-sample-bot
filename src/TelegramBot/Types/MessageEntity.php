<?php
/**
 * Created by PhpStorm.
 * User: iGusev
 * Date: 13/04/16
 * Time: 04:10.
 */

namespace App\TelegramBot\Types;

use App\TelegramBot\BaseType;
use App\TelegramBot\TypeInterface;

class MessageEntity extends BaseType implements TypeInterface
{
  public const TYPE_MENTION = 'mention';
  public const TYPE_HASHTAG = 'hashtag';
  public const TYPE_BOT_COMMAND = 'bot_command';
  public const TYPE_URL = 'url';
  public const TYPE_EMAIL = 'email';
  public const TYPE_BOLD = 'bold';
  public const TYPE_ITALIC = 'italic';
  public const TYPE_CODE = 'code';
  public const TYPE_PRE = 'pre';
  public const TYPE_TEXT_LINK = 'text_link';

  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $requiredParams = ['type', 'offset', 'length'];

  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $map = [
    'type' => true,
    'offset' => true,
    'length' => true,
    'url' => true,
  ];

  /**
   * Type of the entity.
   * One of mention (@username), hashtag, bot_command, url, email, bold (bold text),
   * italic (italic text), code (monowidth string),pre (monowidth block), text_link (for clickable text URLs).
   *
   * @var string
   */
  protected $type;

  /**
   * Offset in UTF-16 code units to the start of the entity.
   *
   * @var int
   */
  protected $offset;

  /**
   * Length of the entity in UTF-16 code units.
   *
   * @var int
   */
  protected $length;

  /**
   * Optional. For “text_link” only, url that will be opened after user taps on the text.
   *
   * @var string
   */
  protected $url;

  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param string $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * @return int
   */
  public function getOffset()
  {
    return $this->offset;
  }

  /**
   * @param int $offset
   */
  public function setOffset($offset)
  {
    $this->offset = $offset;
  }

  /**
   * @return int
   */
  public function getLength()
  {
    return $this->length;
  }

  /**
   * @param int $length
   */
  public function setLength($length)
  {
    $this->length = $length;
  }

  /**
   * @return string
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param string $url
   */
  public function setUrl($url)
  {
    $this->url = $url;
  }
}
