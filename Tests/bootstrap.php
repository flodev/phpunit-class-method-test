<?php

/**
 * loads namespaced class that either contains ClassMethodTest or starts with Tests
 */

spl_autoload_register(function($class) {
    # cancel autoloading when its not a unit test or classMethodTest
    if (false === strpos($class, 'ClassMethodTest') && 0 !== strpos($class, 'Tests')
        && 0 !== strpos($class, 'TestObjects')) {
        return;
    }

    $class = trim($class, '\\');
    $classPath = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class);
    $firstClassPart = substr($classPath, 0, strpos($classPath, DIRECTORY_SEPARATOR));

    switch ($firstClassPart) {
        case 'ClassMethodTest':
            $path = realpath(__DIR__)
                . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . $classPath . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
            break;
        case 'Tests':
            $classPath = substr_replace($classPath, '', 0, strpos($classPath, DIRECTORY_SEPARATOR));
            $path = realpath(__DIR__)
                . DIRECTORY_SEPARATOR
                . $classPath . '.php';

            if (file_exists($path)) {
                require_once $path;
            }
            break;
        case 'TestObjects':
            require_once realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $classPath . '.php';
    }
});