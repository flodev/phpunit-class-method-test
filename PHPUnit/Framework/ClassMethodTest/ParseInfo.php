<?php

/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

class ParseInfo
{
    public static function getClassGeneratorXDebugIndex()
    {
        $refl = new \ReflectionClass('\PHPUnit\Framework\ClassMethodTest\ClassGenerator');
        $classGenFile = file($refl->getFileName());
        $evalLine = null;

        foreach ($classGenFile as $lineNumber => $line) {
            if (strpos($line, 'eval($code);') !== false) {
                $evalLine = $lineNumber;
                break;
            }
        }

        if ($evalLine === null) {
            throw new \PHPUnit_Framework_Exception('Cannot find line where eval is executed.');
        }

        return $refl->getFileName() . '(' . $evalLine . ') : eval()\'d code';
    }
}
