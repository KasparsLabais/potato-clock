<?php

use App\Twitch\Routers\Router;

require __DIR__ . '/../autoloader.php';

$autoloader = new TwitchAutoloader();
$autoloader->register();


include __DIR__ .'/../routes/web.php';
Router::match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
