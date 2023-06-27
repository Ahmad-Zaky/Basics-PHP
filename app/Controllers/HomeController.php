<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    function __invoke()
    {        
        view("home", ["heading" => __("Home")]);
    }
}