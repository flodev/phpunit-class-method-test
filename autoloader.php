<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

spl_autoload_register(function($class) {
    ltrim($class, '\\');
    if (0 !== strpos($class, 'ClassMethodTest')) {
        return;
    }

    require_once __DIR__ . DIRECTORY_SEPARATOR . str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
});