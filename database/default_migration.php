<?php 

use App\Console\Application;
use App\Twitch\Database\Database;
use App\Twitch\Database\Migration;
use App\Twitch\Database\MigrationInterface;
use App\Twitch\Database\Table;

class DefaultMigration implements MigrationInterface  {

    use Migration;

    private static $tableName = 'migrations';

    public static function migrate() : void 
    {

        $table = new Table(self::tableName());
        $table->primary('id');
        $table->dateTime('created_at');
        $table->string('file', 255);
        $table->tinyInt('batch', 8);

        $table->save();
    }

    //TODO: Create a rollback  options after this
    public static function rollBack() : void
    {

    }



}