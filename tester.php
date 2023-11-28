<?php

use Ninja\Cosmic\Application\Application;
use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\Theme\Theme;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->safeLoad();


$theme = Theme::fromThemeFolder(__DIR__ . '/themes/default');
Terminal::withTheme($theme);

$app = new Application(
    name: sprintf("%s %s", Terminal::getTheme()->getAppIcon(), Env::get("APP_NAME")),
    version: Env::getVersion()
);

$app->setAutoExit(false);
$app->run();
