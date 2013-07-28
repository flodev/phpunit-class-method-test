<?php

use PHPUnit\Framework\ClassMethodTest\ClassGenerator;

class ClassGeneratorTest extends PHPUnit_Framework_TestCase
{

    public function testTest()
    {
        $generator = new ClassGenerator(
            $this->getBuildMock(),
            $this->getClassParserMock()
        );

        $proxy = $generator->generateClass();
        $this->assertInstanceOf('PHPUnit\Framework\ClassMethodTest\ClassProxy', $proxy);
        $instance = $proxy->createInstance();
        $this->assertInstanceOf('\Testiii\NewTestClass', $instance);
        $this->assertEquals('hallo', $instance->method1());
    }

    private function getClassParserMock()
    {
        $classParser = $this->getMockBuilder('PHPUnit\Framework\ClassMethodTest\ClassParser')
                ->disableOriginalConstructor()->getMock();
        $classParser->expects($this->any())->method('extractFunction')->will(
            $this->returnValue('
                public function method1() {
                    return "hallo";
                }
            ')
        );

        $testMock = $this->getMock("Tests\ReflectionMock");
        $testMock->expects($this->any())->method('getNamespaceName')->will($this->returnValue('Testiii'));

        $classParser->expects($this->any())->method('getNamespace')->will($this->returnValue('namespace Testiii;'));
        $classParser->expects($this->any())->method('getReflection')->will($this->returnValue($testMock));

        return $classParser;
    }

    private function getBuildMock()
    {
        $methodTestMock = $this->getMockBuilder('PHPUnit\Framework\ClassMethodTest\Build')
                ->disableOriginalConstructor()->getMock();

        $methodTestMock->expects($this->any())->method('get')->will($this->returnCallback(
            function($prop) {
                if ($prop === 'methodTestClassName') {
                    return 'NewTestClass';
                }

                if ($prop === 'propertyMocks') {
                    return array('testMock' => "mock", 'testMock2' => 'mock');
                }

                if ($prop === 'methods') {
                    return array('method1');
                }
            }
        ));

        return $methodTestMock;
    }
}