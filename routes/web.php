<?php

use App\Twitch\Routers\Router;

Router::register("GET", "/", "PageController@index");
Router::register("GET", "/login", ["AuthController", "loginIndex"]);

//Router::printRouters();