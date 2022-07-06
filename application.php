#!/usr/bin/env php
<?php

use App\Command\BotCommand;
use Symfony\Component\Dotenv\Command\DebugCommand;

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands
$application->add(new BotCommand());
//$application->add(new DebugCommand('dev',pathinfo(__FILE__)['dirname']));




$application->run();
