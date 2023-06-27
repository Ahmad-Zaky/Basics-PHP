<?php

namespace Core;

use Core\Contracts\View;

class ViewManager implements View
{
    public function render(string $path = "", array $attributes = []): void
    {
        extract($attributes);

        $path = str_replace(".", DIRECTORY_SEPARATOR, $path) .".view.php";

        require appPath("Views". DIRECTORY_SEPARATOR . $path);
    }
}
