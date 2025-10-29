<?php

namespace App\Console;

class Kernal {


    public function __construct(private Application $application) {}

    public function handle() 
    {
        return $this->application->run();
    }

}