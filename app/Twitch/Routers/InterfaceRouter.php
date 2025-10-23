<?php

namespace App\Twitch\Routers;

interface InterfaceRouter {
    public static function register(string $requestMethod, string $route, string|array $requestController) : void;

}