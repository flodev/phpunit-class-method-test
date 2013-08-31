<?php

/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

use PHPUnit\Framework\ClassMethodTest\ClassParser;
use PHPUnit\Framework\ClassMethodTest\ClassGenerator;
use PHPUnit\Framework\ClassMethodTest\Build;
use PHPUnit\Framework\ClassMethodTest\ParseInfo;

class CodeCoverage
{
    /**
     *
     * @var array
     */
    private $coverageData = null;

    /**
     *
     * @var \ReflectionClass
     */
    private $generatedClass = null;

    /**
     *
     * @param \PHP_CodeCoverage $coverage
     */
    public function __construct(\PHP_CodeCoverage $coverage)
    {
        $this->coverageData = $coverage->getData();
    }

    /**
     *
     * @return bool
     */
    public function hasCoverage()
    {
        return !empty($this->coverageData[ParseInfo::getInstance()->getGeneratedClassFilePath()])
               && class_exists(ParseInfo::getInstance()->getGeneratedClassName(), false)
               && class_exists(ParseInfo::getInstance()->getTestClassName(), false);
    }

    /**
     *
     * @return array
     */
    public function getCoverage()
    {
        $this->generatedClass = $this->getGeneratedClass();

        if (!$this->hasCoverage()) {
            return array();
        }

        $testData = $this->coverageData[ParseInfo::getInstance()->getGeneratedClassFilePath()];

        return array(
            ParseInfo::getInstance()->getTestClassFilePath() =>
                $this->adaptTestData($testData, $this->getLineOffset())
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

        $generatedClassFirstLine = $this->generatedClass->getMethod($firstMethod)->getStartLine();
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

        foreach (ParseInfo::getInstance()->getTestMethods() as $method) {
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
        $lastLineOfGeneratedClass = $this->generatedClass->getEndLine();

        foreach ($testData as $lineNumber => $callers) {
            # break coverage found in offset lines
            if ($lineNumber > $lastLineOfGeneratedClass) {
                break;
            }
            # skip 0 lines
            if ($lineNumber === 0) {
                continue;
            }
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

    /**
     *
     * @return \ReflectionClass
     * @throws \PHPUnit_Framework_Exception
     */
    private function getOriginalClass()
    {
        try {
            return new \ReflectionClass(ParseInfo::getInstance()->getTestClassName());
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Test class is not available', null, $e);
        }
    }

    /**
     *
     * @return \ReflectionClass
     * @throws \PHPUnit_Framework_Exception
     */
    private function getGeneratedClass()
    {
        try {
            return new \ReflectionClass(ParseInfo::getInstance()->getGeneratedClassName());
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Generated class is not available', null, $e);
        }
    }
}
