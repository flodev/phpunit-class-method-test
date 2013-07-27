<?php

spl_autoload_register(function($class) {
    if (false === strpos($class, 'MethodTest')) {
        return;
    }
    $class = trim($class, '\\');
    $classPath = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class);
    $firstClassPart = substr($classPath, 0, strpos($classPath, DIRECTORY_SEPARATOR));

    switch ($firstClassPart) {
        case 'PHPUnit':
           require_once realpath(__DIR__)
            . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . $classPath . '.php';
    }
});