<?php

namespace App\TelegramBot\Types;

use App\TelegramBot\BaseType;
use App\TelegramBot\InvalidArgumentException;
use App\TelegramBot\TypeInterface;

/**
 * Class Location
 * This object represents a point on the map.
 */
class Location extends BaseType implements TypeInterface
{
  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $requiredParams = ['latitude', 'longitude'];

  /**
   * {@inheritdoc}
   *
   * @var array
   */
  protected static $map = [
    'latitude' => true,
    'longitude' => true,
  ];

  /**
   * Longitude as defined by sender.
   *
   * @var float
   */
  protected $longitude;

  /**
   * Latitude as defined by sender.
   *
   * @var float
   */
  protected $latitude;

  /**
   * @return float
   */
  public function getLatitude()
  {
    return $this->latitude;
  }

  /**
   * @param float $latitude
   *
   * @throws InvalidArgumentException
   */
  public function setLatitude($latitude)
  {
    if (is_float($latitude)) {
      $this->latitude = $latitude;
    } else {
      throw new InvalidArgumentException();
    }
  }

  /**
   * @return float
   */
  public function getLongitude()
  {
    return $this->longitude;
  }

  /**
   * @param float $longitude
   *
   * @throws InvalidArgumentException
   */
  public function setLongitude($longitude)
  {
    if (is_float($longitude)) {
      $this->longitude = $longitude;
    } else {
      throw new InvalidArgumentException();
    }
  }
}
