<?php


namespace App\Twitch\Controller;

use DateTimeZone;
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
        foreach($data as $key => $d) {
            $searchVariable = "@var('{$key}')";
            $fileContent = str_replace($searchVariable, $d, $fileContent);
        }


        // ob_start('App\Twitch\Controller\View::parseView');

        // require $fullFilePath;
        // $content = ob_get_contents();
        ob_start();
        echo $fileContent;
        $content = ob_get_contents();

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

        
        if (self::hasValidCacheFile($fullFilePath)) {
            return self::loadCachedFile($fullFilePath);
        }


        // Check for @extends -> need to load a parrent view 
        // Check for @section
        $extends = null; 
        $section = null;

        $sectionContent = [];
        $sectionContentStarted = false;

        $extendsSectionEnabled = false;

        //Rule 1: If there is extends section it needs to have bodySection 
        //Rule 2: if we found first bodySection and there is no extends section, it is fatal.
        //Rule 3: if we find a body section, but there is string content before it, we throw fatal.

        $fileLines = file($fullFilePath);
        $totalContentLength = 0;

        foreach($fileLines as $line) {


            if($sectionContentStarted && strpos($line, '@endsection') === 0) {
                $sectionContentStarted = false;
            }     

            if ($sectionContentStarted) {
                $sectionContent[] = $line;
            } 


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
            } elseif (strpos($line, '@section') === 0 && $extendsSectionEnabled) {
                $section = trim(str_replace(array('@section', '(', ')', '\''), '', $line));
                $sectionContentStarted = true;
            }

       

            $totalContentLength = strlen(string: $line);
        }


        if($extendsSectionEnabled) {
            //we need to preload layout file first 
            $layoutFilePath = self::generateFullFilePath([$extends]);
            //var_dump('Layout path:' ,$layoutFilePath, $extends);die();

            //TODO: cant use extend as it is as it would or ciould hold a path to folders
            $layoutFulFilePath = $layoutFilePath . $extends; 

            // var_dump(file_exists(filename: $fullFilePath), "<br>");
            // var_dump($fullFilePath . "<br>");
            // var_dump(file_exists(filename: trim($layoutFulFilePath)), "<br>");
            // var_dump($layoutFulFilePath, "<br>");

        
            $layoutFile = file(trim($layoutFulFilePath));
            $newFile = [];



            foreach ($layoutFile as $value) {
                if (str_contains(trim($value), "@holder('{$section}')")) {
                    foreach($sectionContent as $sectionLine) {
                        $newFile[] = $sectionLine;
                    }
                } else {
                    $newFile[] = $value;
                }
            }

    
        } else {
            //TODO: add functionality if file is not mixin
        }

        $cachedFiled = self::createCachedFile($fullFilePath, $newFile);
        return $cachedFiled;
    }

    public static function hasValidCacheFile(string $filePath) : bool 
    {

        //TODO: make cache file based on extension
        $cacheFileName = md5($filePath) . '.php';
        
        $basePath = __DIR__ . '/../../../storage/Views/';
        $cachedFileFullPath = $basePath . $cacheFileName;

        if(file_exists($cachedFileFullPath)) {
            
            $fileInfo = new SplFileInfo($cachedFileFullPath);
            

            $dateNow = new \DateTime('NOW', new DateTimeZone('UTC'));

            $fileCreationT = new \DateTime();
            $fileCreationT->setTimestamp($fileInfo->getMTime());
            $fileCreationT->setTimezone(new DateTimeZone('UTC'));


            //TODO: get cach allowed time from config
            $allowedCacheTime = (60*10); // 10 minutes

            $timeDiff = $dateNow->getTimestamp() - $fileCreationT->getTimestamp();

            if ($timeDiff < $allowedCacheTime) {
                return true;
            } 

            //IF file is expired, we need to delete it first
            unlink($cachedFileFullPath);
        }


        return false;
    }

    public static function createCachedFile(string $filePath, array $fileData) : string {


        $cacheFileName = md5($filePath) . '.php';
        
        $basePath = __DIR__ . '/../../../storage/Views/';
        $cachedFileFullPath = $basePath . $cacheFileName;

        $cachFile = fopen($cachedFileFullPath, 'a');
        foreach($fileData as $data) {
            fwrite($cachFile, $data);
        }

        fclose($cachFile);
        return file_get_contents($cachedFileFullPath);
    }

    public static function loadCachedFile(string $filePath) : string {
        $cacheFileName = md5($filePath) . '.php';
        
        $basePath = __DIR__ . '/../../../storage/Views/';
        $cachedFileFullPath = $basePath . $cacheFileName;

        return file_get_contents($cachedFileFullPath);
    }

    public static function parseView($buffer) 
    {
        var_dump($buffer);die();
        return $buffer;
    } 



    //step 2 load up parrent file if it have one
    //Step 3 parse variables 


}