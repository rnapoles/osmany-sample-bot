<?php

namespace App\TelegramBot\Types;

abstract class ArrayOfUser
{
  public static function fromResponse($data)
  {
    $arrayOfUsers = [];
    foreach ($data as $user) {
      $arrayOfUsers[] = User::fromResponse($user);
    }

    return $arrayOfUsers;
  }
}
