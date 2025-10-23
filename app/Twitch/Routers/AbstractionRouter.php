<?php 

namespace App\Twitch\Routers;

abstract class AbstractionRouter {
    

    public function parseStringRouteController(string $routeController) : array {
        $controller = explode("@", $routeController);
        return [
            "class" => "App\\Controller\\{$controller[0]}",
            "action" => $controller[1]
        ];
    }


}