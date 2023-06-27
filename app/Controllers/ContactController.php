<?php

namespace App\Controllers;

use Core\Controller;

class ContactController extends Controller
{
    function __invoke()
    {
        view("contact", ["heading" => __('Contact Us')]);
    }
}