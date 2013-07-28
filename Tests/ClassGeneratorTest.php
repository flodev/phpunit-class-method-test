<?php

use PHPUnit\Framework\MethodTest\ClassGenerator;

class ClassGeneratorTest extends PHPUnit_Framework_TestCase
{

    public function testTest()
    {
        $generator = new ClassGenerator(
            $this->getMethodTestMock(),
            $this->getClassParserMock()
        );
        $generator->generateClass();
    }

    private function getClassParserMock()
    {
        $classParser = $this->getMockBuilder('PHPUnit\Framework\MethodTest\ClassParser')
                ->disableOriginalConstructor()->getMock();
        $classParser->expects($this->any())->method('extractFunction')->will(
            $this->returnValue('
                public function method1() {
                }
            ')
        );

        $classParser->expects($this->any())->method('getNamespace')->will($this->returnValue('namespace TestNs;'));

        return $classParser;
    }

    private function getMethodTestMock()
    {
        $methodTestMock = $this->getMockBuilder('PHPUnit\Framework\MethodTest\MethodTest')
                ->disableOriginalConstructor()->getMock();

        $methodTestMock->expects($this->any())->method('get')->will($this->returnCallback(
            function($prop) {
                if ($prop === 'methodTestClassName') {
                    return 'test';
                }

                if ($prop === 'methods') {
                    return array('method1');
                }
            }
        ));
        return $methodTestMock;
    }
}