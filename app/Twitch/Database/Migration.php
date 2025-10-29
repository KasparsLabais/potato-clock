<?php


namespace App\Twitch\Database;

trait Migration {


    public static function tableName() {
        return static::$tableName;
    }


    public function table() 
    {

    }

}