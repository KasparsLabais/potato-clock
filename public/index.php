<?php

use App\Twitch\App\Application;
use App\Twitch\Database\Database;
use App\Twitch\Routers\Router;

require __DIR__ . '/../autoloader.php';

$autoloader = new TwitchAutoloader();
$autoloader->register();


$app = new Application();
$app['database'] = new Database();


include __DIR__ .'/../routes/web.php';
Router::match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
