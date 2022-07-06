<?php

namespace App\TelegramBot\Types;

use App\TelegramBot\BaseType;
use App\TelegramBot\InvalidArgumentException;
use App\TelegramBot\TypeInterface;

/**
 * Class UserProfilePhotos
 * This object represent a user's profile pictures.
 */
class UserProfilePhotos extends BaseType implements TypeInterface
{
  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $requiredParams = ['total_count', 'photos'];

  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $map = [
    'total_count' => true,
    'photos' => ArrayOfArrayOfPhotoSize::class,
  ];

  /**
   * Total number of profile pictures the target user has.
   *
   * @var int
   */
  protected $totalCount;

  /**
   * Requested profile pictures (in up to 4 sizes each).
   * Array of Array of \App\TelegramBot\Types\PhotoSize.
   *
   * @var array
   */
  protected $photos;

  /**
   * @return array
   */
  public function getPhotos()
  {
    return $this->photos;
  }

  /**
   * @param array $photos
   */
  public function setPhotos($photos)
  {
    $this->photos = $photos;
  }

  /**
   * @return int
   */
  public function getTotalCount()
  {
    return $this->totalCount;
  }

  /**
   * @param int $totalCount
   *
   * @throws InvalidArgumentException
   */
  public function setTotalCount($totalCount)
  {
    if (is_integer($totalCount)) {
      $this->totalCount = $totalCount;
    } else {
      throw new InvalidArgumentException();
    }
  }
}
