<?php

/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace ClassMethodTest;

use ClassMethodTest\ParseInfo;

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
     * @var \ReflectionClass
     */
    private $originalClass = null;

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
        if (!$this->hasCoverage()) {
            return array();
        }
        
        $this->generatedClass = $this->getGeneratedClass();
        $this->originalClass = $this->getOriginalClass();

        $testData = $this->coverageData[ParseInfo::getInstance()->getGeneratedClassFilePath()];

        return array(
            ParseInfo::getInstance()->getTestClassFilePath() => $this->adaptTestData($testData)
        );
    }

    /**
     *
     * @param array $testData
     * @param int $lineOffset
     * @return array
     */
    private function adaptTestData(array $testData)
    {
        $newTestData = array();
        $calledMethods = ParseInfo::getInstance()->getCalledMethods();

        foreach ($calledMethods as $calledMethod) {
            $reflMethod = $this->getGeneratedMethod($calledMethod);
            $generatedMethodStartLine = $reflMethod->getStartLine();
            $generatedMethodEndLine = $reflMethod->getEndLine();
            $methodStartLine = $this->getOriginalMethod($calledMethod)->getStartLine();

            for ($i = $generatedMethodStartLine; $i <= $generatedMethodEndLine; $i++, $methodStartLine++) {
                if (!array_key_exists($i, $testData)) {
                    continue;
                }
                $newTestData[$methodStartLine] = count($testData[$i]) > 0 ? 1 : -1;
            }
        }

        return $newTestData;
    }

    /**
     *
     * @param string $method
     * @return \ReflectionMethod
     */
    private function getGeneratedMethod($method)
    {
        try {
            return $this->generatedClass->getMethod($method);
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Cannot find method on generated class: ' . $method, null, $e);
        }
    }

    /**
     *
     * @param string $method
     * @return \ReflectionMethod
     */
    private function getOriginalMethod($method)
    {
        try {
            return $this->originalClass->getMethod($method);
        } catch (\ReflectionException $e) {
            throw new \PHPUnit_Framework_Exception('Cannot find method on original class: ' . $method, null, $e);
        }
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
