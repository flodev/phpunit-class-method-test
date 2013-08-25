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
    /**
     *
     * @var array
     */
    private $coverageData = null;

    public function __construct(\PHP_CodeCoverage $coverage)
    {
        $this->coverageData = $coverage->getData();
    }

    public function hasCoverage()
    {
        return !empty($this->coverageData[ClassGenerator::getClassFilePath()]);
    }

    /**
     *
     * @return array
     */
    public function getCoverage()
    {
        if (!$this->hasCoverage()) {
            return array();
        }

        $testData = $this->coverageData[ClassGenerator::getClassFilePath()];

        return array(
            ClassParser::getClassFilePath() => $this->adaptTestData($testData, $this->getLineOffset())
        );
    }

    /**
     *
     * @return int
     */
    private function getLineOffset()
    {
        $originalClass = $this->getOriginalClass();
        list($originalClassFirstLine, $firstMethod) = $this->getTestMethodData($originalClass);

        $generatedClass = $this->getGeneratedClass();
        $generatedClassFirstLine = $generatedClass->getMethod($firstMethod)->getStartLine();
        $lineOffset = $originalClassFirstLine - $generatedClassFirstLine;
        return $lineOffset;
    }

    /**
     *
     * @param \ReflectionClass $originalClass
     * @return array
     */
    private function getTestMethodData(\ReflectionClass $originalClass)
    {
        $originalClassFirstLine = null;
        $firstMethod = null;

        foreach (Build::getTestMethods() as $method) {
            $reflMethod = $originalClass->getMethod($method);
            if ($reflMethod->getStartLine() < $originalClassFirstLine || $originalClassFirstLine === null) {
                $originalClassFirstLine = $reflMethod->getStartLine();
                $firstMethod = $method;
            }
        }

        return array($originalClassFirstLine, $firstMethod);
    }

    /**
     *
     * @param array $testData
     * @param int $lineOffset
     * @return array
     */
    private function adaptTestData(array $testData, $lineOffset)
    {
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

        return $newTestData;
    }

    private function getOriginalClass()
    {
        try {
            return new \ReflectionClass(ClassParser::getClassName());
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Test class is not available', null, $e);
        }
    }

    private function getGeneratedClass()
    {
        try {
            return new \ReflectionClass(ClassGenerator::getGeneratedClassName());
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Generated class is not available', null, $e);
        }
    }
}
