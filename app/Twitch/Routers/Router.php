<?php

namespace App\Twitch\Routers;

class Router extends AbstractionRouter implements InterfaceRouter {
    private static $routes = [];

    public static function register(string $requestMethod, string $route, string|array $requestController) : void {

        if (is_string($requestController)) {
            $requestController = (new self)->parseStringRouteController($requestController);
        } else {
            $requestController = [            
                "class" => "App\\Controller\\{$requestController[0]}",
                "action" => $requestController[1]
            ];
        }

        self::$routes[$route][$requestMethod] = $requestController;
    }


    public static function match($reqMethod, $reqUri) 
    {

        //var_dump(self::$routes, $reqUri, $reqMethod);die();

        //attempt 1 try to match exact first
        if(isset(self::$routes[$reqUri][$reqMethod])) {

            $controllerClass =  self::$routes[$reqUri][$reqMethod]['class'];
            $controllerAction = self::$routes[$reqUri][$reqMethod]['action'];

            $routeController = new $controllerClass();
            if(method_exists($routeController, $controllerAction)) {
                return call_user_func_array([$routeController, $controllerAction], []);
            }
        }

        die('failed to find a registered route');


        //attempt 2 if exact match is not working we are looking at variable type url like {variable} / {variable}


    }

    public static function printRouters() {
        var_dump(self::$routes);die();
    }
}