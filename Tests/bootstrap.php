<?php

/**
 * loads namespaced class that either contains ClassMethodTest or starts with Tests
 */

spl_autoload_register(function($class) {
    if (false === strpos($class, 'ClassMethodTest') && 0 !== strpos($class, 'Tests')) {
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
            break;
        case 'Tests':
            $classPath = substr_replace($classPath, '', 0, strpos($classPath, DIRECTORY_SEPARATOR));
            require_once realpath(__DIR__)
                . DIRECTORY_SEPARATOR
                . $classPath . '.php';
            break;
    }
});