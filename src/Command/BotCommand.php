<?php

namespace App\Command;

use App\Bot\BotClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;


const DEV_ID = '821290682';

class BotCommand extends Command
{
  protected static $defaultName = 'app:bot';


  public function __construct()
  {
    parent::__construct();
  }

  protected function configure()
  {
    $this
        ->setDescription('Run bot');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);
    $msg = '';
    $delay = 60;
    $maxDelay = 3600;

    start:
        try {
          $dotenv = new Dotenv();
          $dotenv->usePutenv(true);
          $dotenv->loadEnv(__DIR__. '/../../.env','null','dev',[],true);

          //$r = (new Dotenv())->parse(file_get_contents(__DIR__. '/../../.env'));
          //var_dump($r);

          if (!empty($msg)) {
            $this->notifyError('Info', 'Recuperandome de un error.');
            $msg = '';
          }

          $this->notifyError('Info', 'Iniciando el bot....');
          $this->runBot($io);
        } catch (\Exception $e) {
          $this->notifyError('Error en el sistema', utf8_encode($e->getMessage()));

          $msg = "---------------------------------\n";
          $msg .= 'Code: '.$e->getCode()."\n";
          $msg .= 'Message: '.$e->getMessage()."\n";
          $msg .= 'File: '.$e->getFile()."\n";
          $msg .= 'Line: '.$e->getLine()."\n";
          $msg .= $e->getTraceAsString()."\n";
          $msg .= "********************************\n";
          // $this->notifyError("Error",$msg);
          echo $msg;

          sleep($delay);
          if ($delay < $maxDelay) {
            $delay *= 2;
          }

          goto start;
        }

    return 0;
  }

  private function runBot($io)
  {
    set_time_limit(0);

    $BOT_API_KEY = getenv('BOT_API_KEY');
    echo 'BOT_API_KEY:'. $BOT_API_KEY . "\n\n";

    $isDevMode = 'dev' === getenv('APP_ENV');
    $this->allowTest = ((int) getenv('ALLOW_TEST')) === 1;
    if ($isDevMode) {
      $this->allowTest = true;
    }

    $bot = new BotClient(
      $BOT_API_KEY
    );
    // $bot->setProxy('127.0.0.1:1080');
    $this->bot = $bot;
    $scope = $this;
    $this->notifyError('Info', 'Registrando comandos');
    $bot->registerCommands();
    $this->notifyError('Info', 'Listo para procesar los mensajes');

    while (true) {
      echo "processUpdates\n";
      $bot->processUpdates();
    }
  }

  private function notifyError($subject, $body)
  {
    try {
      $this->sendTelegramMessage($subject, $body, DEV_ID);
      // $this->sendTelegramMessage($subject,$body,'1219108145');
    } catch (\Exception $e) {
      echo "error enviando notificación\n";
    }
  }

  private function sendTelegramMessage($subject, $body, $chatId)
  {
    // sleep(5);

    $body = $subject."\n".str_repeat('=', strlen($subject))."\n".$body;
    // echo $body . "\n";

    $botToken = getenv('BOT_API_KEY');
    $website = 'https://api.telegram.org/bot'.$botToken.'/sendMessage';

    $params = [
      'chat_id' => $chatId,
      'text' => $body,
    ];

    $ch = curl_init($website);

    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
    // curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:1080');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // timeout in seconds
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);

    curl_close($ch);
  }

}
