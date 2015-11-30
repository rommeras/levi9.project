<?php

spl_autoload_register(function($className) {
    $path = str_replace("\\", "/", $className.".php");
    $path = ltrim($path,'/');
    if (file_exists(__DIR__ . '/' . $path))
        require_once($path);
});

\Core\Router::start();