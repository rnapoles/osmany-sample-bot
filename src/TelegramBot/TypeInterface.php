<?php

namespace App\TelegramBot;

interface TypeInterface
{
  public static function fromResponse($data);
}
