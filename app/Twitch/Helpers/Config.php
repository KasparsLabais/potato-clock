<?php


namespace App\Twitch\Helpers;

trait Config {


    public static function config($section, $key) 
    {
        $config = require(__DIR__ . '/../../../config.php');
        return $config[$section][$key];
    }

}