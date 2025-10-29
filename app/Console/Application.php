<?php


namespace App\Console;

use App\Twitch\Database\Database;
use DirectoryIterator;

class Application {

    private array $commandList = [];
    private string $commandName = '';

    public function __construct(private string $nameSpace, private array $argv) {}


    public function run() 
    {

        $app = new \App\Twitch\App\Application();
        $app['database'] = new Database();

        $this->loadCommands();
        $this->setArguments();

        return (new $this->commandList[$this->commandName])->execute($app, $this->argv);
    }

    private function loadCommands() : void  
    {
        $directoryList = new DirectoryIterator(__DIR__ . '/Commands/');
        foreach($directoryList as $directory) {
            if(!$directory->isFile()) {
                continue;
            }

            // var_dump(pathinfo($directory, PATHINFO_FILENAME));die();
            $className = $this->nameSpace . pathinfo($directory, PATHINFO_FILENAME);
            $commandName = (new $className)->getName();

            $this->commandList[$commandName] = $className;
        }
    }

    private function setArguments() : void 
    {

        //TODO: add later validation if command is passed correctly with all ne bits and bobs
        //foreach($this->argv as $arg) {
        //}

        $this->commandName = $this->argv[1];
        $this->argv = array_slice($this->argv, 2); //first is brain second is command name
    }

}