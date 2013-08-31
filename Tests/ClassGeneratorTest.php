<?php

use PHPUnit\Framework\ClassMethodTest\ClassGenerator;

class ClassGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateNewClass()
    {
        $generator = new ClassGenerator(
            $this->getBuildMock(),
            $this->getClassParserMock()
        );

        $proxy = $generator->generateClass();
        $this->assertInstanceOf('PHPUnit\Framework\ClassMethodTest\ClassProxy', $proxy);
        $instance = $proxy->createInstance();
        $this->assertEquals('hallo', $proxy->createInstance()->exec('method1'));
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
        $classParser->expects($this->any())->method('getShortName')->will($this->returnValue('NewTestClass'));
        $classParser->expects($this->any())->method('getReflection')->will($this->returnValue($testMock));
        $classParser->expects($this->any())->method('getOrderedMethods')->will($this->returnValue(array('method1')));
        $classParser->expects($this->any())->method('extractConstants')->will($this->returnValue(array(
            'const TEST1 = "hallo";',
            'const TEST2 = "hallo";',
        )));

        return $classParser;
    }

    private function getBuildMock()
    {
        $buildMock = $this->getMockBuilder('PHPUnit\Framework\ClassMethodTest\Build')
                ->disableOriginalConstructor()->getMock();

        $buildMock->expects($this->any())->method('hasClassPropertyMocks')->will($this->returnValue(true));
        $buildMock->expects($this->any())->method('get')->will($this->returnCallback(
            function($prop) {
                if ($prop === 'methodTestClassName') {
                    return 'NewTestClass';
                }

                if ($prop === 'propertyMocks') {
                    return array();
                }

                if ($prop === 'methods') {
                    return array('method1');
                }
            }
        ));

        return $buildMock;
    }
}