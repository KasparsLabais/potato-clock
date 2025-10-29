<?php

namespace App\Console\Commands;

use App\Twitch\App\Application;
use DefaultMigration;

class Migration {
 
    
    private static $name = 'db:migrate';

    public function execute(Application $app, array $argv = []) : int  
    {

        //var_dump($app['database']);
        
        // check for migrations file exists
        if(!$app['database']->hasMigrationTable()) {
            //we need to create initial migration;
            $app['database']->runMigration('DefaultMigration', 'default_migration');
        }

        //load classes with Directiory Iterator
        //get class name by get defined classes, and either compare before / after or use end() 
        //Run each found classes migrate method

        echo "im running migration";
        return 0;
    }

    public static function getName() 
    {
        return static::$name;
    }

}