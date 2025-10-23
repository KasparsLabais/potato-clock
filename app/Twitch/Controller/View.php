<?php


namespace App\Twitch\Controller;

use SplFileInfo;


class View {

    
    //step 1 get file from path 
    public static function render($view, $data) 
    {

        $viewObj = explode('.', $view);
        $fileName = $viewObj[count($viewObj) - 1] . '.php';


        $fullFilePath = self::generateFullFilePath($viewObj);
        $fullFilePath .= $fileName;
        
        if (!file_exists($fullFilePath)) {
            //TODO: add error handling if view is not found
        }


        $fileContent = self::findOrCreateCachedFile($fullFilePath);

        // var_dump($fileGetContent);die


        // ob_start('App\Twitch\Controller\View::parseView');

        // require $fullFilePath;
        // $content = ob_get_contents();

        return $content;
    }

    public static function generateFullFilePath(array $viewObj) : string 
    {

        //TODO: Replace with config and ROOT dir
        $basePath = __DIR__ . '/../../../resources/Views/';
        if (count($viewObj) == 1) {
            return $basePath;
        }

        for($i = 0; $i < count($viewObj) - 1; $i++) {
            $basePath .= $viewObj[$i];
        }

        return "{$basePath}/";
    }


    public static function findOrCreateCachedFile(string $fullFilePath) : string
    {

        $cacheFileName = md5($fullFilePath);
        if (self::hasValidCacheFile($cacheFileName)) {
            //If true we return a already pre-generated file 
        }


        // Check for @extends -> need to load a parrent view 
        // Check for @section
        $extends = null; 
        $section = null;

        $extendsSectionEnabled = false;

        //Rule 1: If there is extends section it needs to have bodySection 
        //Rule 2: if we found first bodySection and there is no extends section, it is fatal.
        //Rule 3: if we find a body section, but there is string content before it, we throw fatal.

        $fileLines = file($fullFilePath);
        $totalContentLength = 0;

        foreach($fileLines as $line) {

            if(strpos($line, '@extends') === 0) {
                if ($totalContentLength > 0) {
                    //Fatal error there is content before extends 
                    die('Fatal error: there is content before extends');
                }

        
                $extends = str_replace(array('@extends', '(', ')', '\''), '', $line);
                $extendsSectionEnabled = true;
            }
            
            if(strpos($line, '@section') === 0 && !$extendsSectionEnabled) {
                //We want only throw error if condition 2 does not apply
                die('Fatal error: there is no extend section but have body section');
            }
            

            $totalContentLength = strlen(string: $line);

        }

        if($extendsSectionEnabled) {
            //we need to preload layout file first 
            $layoutFilePath = self::generateFullFilePath([$extends]);
            //var_dump('Layout path:' ,$layoutFilePath, $extends);die();

            //TODO: cant use extend as it is as it would or ciould hold a path to folders
            $layoutFulFilePath = $layoutFilePath . $extends; 

            var_dump(file_exists($layoutFulFilePath));
            var_dump($fullFilePath);
            var_dump($layoutFulFilePath);
            $layoutFile = file_get_contents($layoutFulFilePath);
            var_dump($layoutFile);die();
        
        }


        // foreach($fileContent as $ct) {
        //     var_dump($ct);die();
        // }
        //var_dump($fileContent);die();
        die();
    }

    public static function hasValidCacheFile(string $cachedFileName) : bool 
    {
        if(file_exists($cachedFileName)) {
            
            $fileInfo = new SplFileInfo($cachedFileName);
            var_dump($fileInfo);die();


            return true;
        }


        return false;
    }

    public static function parseView($buffer) 
    {
        var_dump($buffer);die();
        return $buffer;
    } 



    //step 2 load up parrent file if it have one
    //Step 3 parse variables 


}