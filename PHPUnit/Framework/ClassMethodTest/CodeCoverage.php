<?php

/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

use PHPUnit\Framework\ClassMethodTest\ClassParser;
use PHPUnit\Framework\ClassMethodTest\ClassGenerator;
use PHPUnit\Framework\ClassMethodTest\Build;

class CodeCoverage
{
    public static function getFilePath()
    {
//        return ClassGenerator::getTestFilePath();
    }

    public static function getStartLine()
    {
        return ClassParser::getStartLine();
    }

    public static function getCoverage(\PHP_CodeCoverage $coverage)
    {
        $data = $coverage->getData();

        if (empty($data[ClassGenerator::getClassFilePath()])) {
            return array();
        }
        $testData = $data[ClassGenerator::getClassFilePath()];
        $methods = Build::getTestMethods();
        $className = ClassParser::getClassName();


        try {
            $class = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Test class is not available', null, $e);
        }

        $smallestLine = null;
        $firstMethod = null;
        foreach ($methods as $method) {
            $reflMethod = $class->getMethod($method);
            if ($reflMethod->getStartLine() < $smallestLine || $smallestLine === null) {
                $smallestLine = $reflMethod->getStartLine();
                $firstMethod = $method;
            }
            $reflMethod->getStartLine();
        }


        $generatedClass = new \ReflectionClass(ClassGenerator::getGeneratedClassName());
        $startLine = $generatedClass->getMethod($firstMethod)->getStartLine();


        $lineOffset = $smallestLine - $startLine;
        $newTestData = array();
        $isConstructorSkipped = false;
        $lastLineNumber = null;

        foreach ($testData as $lineNumber => $callers) {
            if (!$isConstructorSkipped) {
                if ($lastLineNumber !== null && ($lineNumber - $lastLineNumber) > 1) {
                    $isConstructorSkipped = true;
                } else {
                    $lastLineNumber = $lineNumber;
                    continue;
                }
            }
            $newTestData[$lineNumber + $lineOffset] = count($callers) > 0 ? 1 : -1;
        }

        return array(ClassParser::getClassFilePath() => $newTestData);
    }
}
