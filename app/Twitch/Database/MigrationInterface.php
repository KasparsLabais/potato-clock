<?php

namespace App\Twitch\Database;

interface MigrationInterface 
{

    public static function migrate() : void;
    public static function rollBack() : void;

}