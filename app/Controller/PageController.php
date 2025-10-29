<?php

namespace App\Controller;

use App\Twitch\Controller\Controller;
use App\Twitch\Controller\View;

class PageController extends Controller {


    public function index() 
    {
        return View::render('pages.index', ['title' => 'Twitch Page', 'dynamicTitle' => 'Im changing title']);
    }

}