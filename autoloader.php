<?php 

class TwitchAutoloader {

    private $prefixes = [
        'App\\Twitch\\' => __DIR__ . '/app/Twitch/',
        'App\\Controller\\' => __DIR__ . '/app/Controller/'
    ];


    public function register() {
        spl_autoload_register([$this, "loadClass"]);
    }

    public function loadClass($class): bool {
        
        if (!($classPath = $this->isKnownPrefix($class))) {
            return false;
        }


        $subClass = substr($class,  $classPath['len'], strlen($class));
        $filePath = $classPath['path'] . str_replace('\\', '/', $subClass) . '.php';


        if (!file_exists($filePath)) {
            return false;
        }

        
        require $filePath;
        return true;
    }

    public function isKnownPrefix(string $className) : bool|array {
        foreach($this->prefixes as $key => $path) {

            $prefixLen = strlen($key);
            if(strncmp($className, $key, $prefixLen) === 0) {
                return ['path' => $path, 'len' => $prefixLen];
            }
            
        }

        return false;
    }

}




// function autoLoad($className) :void  {
//     //var_dump("class to resolve", $className);    die();
//     //string(16) "class to resolve" string(25) "App\Twitch\Routers\Router"




// }

// spl_autoload_register('autoLoad');