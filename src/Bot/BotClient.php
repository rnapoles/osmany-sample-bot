<?php

namespace App\Bot;

use App\TelegramBot\Client as BotApiClient;
use App\TelegramBot\Types\ReplyKeyboardMarkup;
use App\TelegramBot\Types\ReplyKeyboardRemove;
use App\TelegramBot\Types\Inline\InlineKeyboardMarkup;
use App\TelegramBot\Types\Update as TelegramUpdate;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionFunction;

const BOT_NAME = 'OsmanyTestBot';

class BotClient extends BotApiClient
{
  private $lastUpdateId = null;
  private $botCommands = [];

  public function __construct(
    $token,
  ) {
    parent::__construct($token);
  }

  public function processUpdates()
  {
    if ($this->lastUpdateId) {
      $updates = $this->getUpdates($this->lastUpdateId + 1, 1);
    } else {
      $updates = $this->getUpdates();
    }

    $this->handle($updates);
    foreach ($updates as $update) {
      $this->lastUpdateId = $update->getUpdateId();
      // $this->processUpdate($update);
    }
  }

  private function addBotCommandAlias($name, $alias)
  {
    if (array_key_exists($name, $this->botCommands)) {
      $fn = $this->botCommands[$name];
      $this->addBotCommand($alias, $fn);
    }
  }

  private function addBotCommand($name, \Closure $action)
  {
    $this->botCommands[$name] = $action;
    $this->command($name, $action);
  }

  private function onContact(\Closure $action)
  {
    $this->on(
      self::invokeAction($action),
      function (TelegramUpdate $update) {
        $message = $update->getMessage();

        if (!$message) {
          return true;
        }

        $contact = $message->getContact();

        return null !== $contact;
      }
    );
  }

  private function onNewChatMembers(\Closure $action)
  {
    $this->on(
      self::invokeAction($action),
      function (TelegramUpdate $update) {
        $message = $update->getMessage();
        if (!$message) {
          return false;
        }

        $members = $message->getNewChatMembers();
        if (is_array($members)) {
          if (count($members)) {
            return true;
          }
        }

        return false;
      }
    );
  }

  private function hears($regex, \Closure $action)
  {
    $this->on(self::invokeAction($action), self::match($regex));
  }

  protected static function invokeAction(\Closure $action)
  {
    return function (TelegramUpdate $update) use ($action) {
      $message = $update->getMessage();
      if (!$message) {
        return true;
      }

      $parameters = [$message];
      $action = new ReflectionFunction($action);
      $action->invokeArgs($parameters);

      return false;
    };
  }

  protected static function match($regex)
  {
    return function (TelegramUpdate $update) use ($regex) {
      $message = $update->getMessage();
      if (is_null($message) || !strlen($message->getText() ?? '')) {
        return false;
      }

      return 1 === preg_match($regex, $message->getText());
    };
  }

  public function registerCommands()
  {
    $bot = $this;
    $scope = $this;

    $processAny = function ($message) {

      $telId = $message->getChat()->getId();
      
      $buttons = [
        [
          [
            'text' => 'text 1'
          ],
          [
            'text' => 'text 2'
          ],
          [
            'text' => 'text 3'
          ],
        ],
      ];
      
      $msg = 'prueba';
      $keyboard = new ReplyKeyboardMarkup($buttons, false); // true for one-time keyboard
      $this->sendMessage($telId, $msg, null, false, null, $keyboard);
    };

    $this->addBotCommand(
      'start',
      function ($message) use ($bot, $processAny) {
        $isPrivate = 'private' == $message->getChat()->getType();
        if (!$isPrivate) {
          $processAny($message);
        } else {
          $telId = $message->getChat()->getId();
          // $messageId = $message->getMessageId();
          // $telUser = $message->getFrom();

          $msg = 'Para proceder, nesecitamos que nos';
          $msg .= 'comparta sú numero de teléfono. Presione ';
          $msg .= 'el botón que aparece a continuación.';

          $buttons = [
            [
              [
                'text' => 'Enviar número de teléfono', 'request_contact' => true,
              ],
            ],
          ];

          $keyboard = new ReplyKeyboardMarkup($buttons, true); // true for one-time keyboard
          $bot->sendMessage($telId, $msg, null, false, null, $keyboard);
        }

      }
    );

    $this->addBotCommand('menu', function ($message) use ($bot,$scope) {

      $telId = $message->getChat()->getId();
      $messageId = $message->getMessageId();
      $telUser = $message->getFrom();

      $keyboard = new InlineKeyboardMarkup(
        [
          [
            ['text' => "\u{1F3B0}  Menu 1", 'callback_data' => 'menu-1'],
            ['text' => "\u{1F3B0}  Menu 2", 'callback_data' => 'menu-2'],
            ['text' => "\u{1F3B0}  Menu 3", 'callback_data' => 'menu-3'],
          ],
          [
            ['text' => 'Info', 'callback_data' => 'help'],
          ]
        ]
      );

      $bot->sendMessage($telId,'Menu principal', null, false, null, $keyboard);
    });

    $this->addBotCommand(
      'hola1',
      function ($message) use ($bot) {
        $telId = $message->getChat()->getId();

        $bot->sendMessage($telId, 'Hola 1', null, false, null, null);
      }
    );

    $this->addBotCommand(
      'hola2',
      function ($message) use ($bot) {
        $telId = $message->getChat()->getId();

        $bot->sendMessage($telId, 'Hola 2', null, false, null, null);
      }
    );

    $this->addBotCommand(
      'hola3',
      function ($message) use ($bot) {
        $telId = $message->getChat()->getId();

        $bot->sendMessage($telId, 'Hola 3', null, false, null, null);
      }
    );

    $this->hears('/^\d+$/',function ($message) use ($bot,$scope) {
      $telId = $message->getChat()->getId();
       $isPrivate = $message->getChat()->getType() == 'private';
      $msg = 'Se escribió un número: ' . $message->getText();
      $bot->sendMessage($telId, $msg, null, false, null, $keyboard);
    });

    //$this->hears('/.*/',$processAny);

    $this->onContact(
      function ($message) use ($bot, $scope) {
        $telId = $message->getChat()->getId();
        $keyboard = new ReplyKeyboardRemove();
        // $messageId = $message->getMessageId();

        if (-1 != $telId) {
         
          $contact = $message->getContact();
          $phoneNumber = $contact->getPhoneNumber();

          $keyboard = new ReplyKeyboardRemove();
          $bot->sendMessage($telId, 'Su telefono es ' . $phoneNumber, null, false, null, $keyboard);
        }
      }
    );

    $this->onNewChatMembers(
      function ($message) use ($bot) {
        // $messageId = $message->getMessageId();
        $telId = $message->getChat()->getId();
        $chat = $message->getChat();

        $members = $message->getNewChatMembers();
        foreach ($members as $user) {
          $username = $user->getUsername();
          $firstName = $user->getFirstName();

          $responseMsg = "Bienvenido $firstName\n";
          if ($username) {
            $responseMsg = "Bienvenido $firstName(@$username)\n";
          }
          $responseMsg .= 'escribeme a @' . BOT_NAME;

          $bot->sendMessage($telId, $responseMsg, null, false, null, null);
        }

      }
    );

    //callbackQuery
    $bot->callbackQuery(function ($callbackQuery) use ($bot,$scope) {

      //var_dump($callbackQuery);

      $message = $callbackQuery->getMessage();
      $userId = $callbackQuery->getFrom()->getId();
      $telId = $message->getChat()->getId();
      $messageId = $callbackQuery->getInlineMessageId();
      $data = $callbackQuery->getData();
      
      if(!$messageId){
        $messageId = $message->getMessageId();
      }

      $msg = 'Ejecutar función para procesar ' . $data;
      $bot->sendMessage($telId,$msg, null, false, null, null);
    });


  }
}
