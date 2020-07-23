<?php

if(file_exists("logs.txt")){
    echo file_get_contents("logs.txt");
} else {
    echo "file 'logs.txt' not exist.";
}

$txt = "user id date";
$myfile = file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);