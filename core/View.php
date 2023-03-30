<?php

namespace Core;

class View
{
    public function render($path = "", $attributes = []) 
    {
        extract($attributes);

        $path = str_replace(".", DIRECTORY_SEPARATOR, $path) .".view.php";

        require appPath("Views". DIRECTORY_SEPARATOR . $path);
    }
}
