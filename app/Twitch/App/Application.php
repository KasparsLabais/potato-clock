<?php


namespace App\Twitch\App;

use App\Twitch\Database\Database;
use ArrayAccess;

class Application implements ArrayAccess {

    //public $db = null;

    public static $registeredProperties = [];

    public function __construct()//Database $database) 
    {
        //$this->db = $database;
    }

    public function getDB()
    {    
        //return self::$db;
    }



    public function offsetSet(mixed $offset, mixed $value): void 
    {
        if(is_null($offset)) {
            self::$registeredProperties[] = $value;
        } else {
            self::$registeredProperties[$offset] = $value;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? self::$registeredProperties[$offset] : null;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset(self::$registeredProperties[$offset]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset(self::$registeredProperties[$offset]);
    }

}