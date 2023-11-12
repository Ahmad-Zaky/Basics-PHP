<?php

use Core\CommandColors;

require dirname(__DIR__) .'/Commands/kernal.php';

$makeable = getMakeable($argv); 
$name = getMakeableName($argv);

try {
    make($makeable, $name);
} catch (\Throwable $th) {
    logging($th->getMessage(), CommandColors::RED_COLOR, false);
}