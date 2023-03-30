<?php

namespace App\Controllers;

use Core\Controller;

class AboutController extends Controller
{
    function __invoke()
    {
        view("about", ["heading" => 'About Us']);
    }
}