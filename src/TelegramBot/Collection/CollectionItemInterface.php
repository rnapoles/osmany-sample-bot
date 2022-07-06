<?php

namespace App\TelegramBot\Collection;

interface CollectionItemInterface
{
  public function toJson($inner = false);
}
